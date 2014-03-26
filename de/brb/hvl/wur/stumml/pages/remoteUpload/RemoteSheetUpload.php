<?php
namespace org\fktt\bstlist\pages\remoteUpload;

import('de_brb_hvl_wur_stumml_cmd_PostRequestCmd');
import('de_brb_hvl_wur_stumml_io_File');
import('de_brb_hvl_wur_stumml_pages_Frame');
import('de_brb_hvl_wur_stumml_util_logging_FileLogger');
use org\fktt\bstlist\pages\Frame;
use org\fktt\bstlist\io\File;
use org\fktt\bstlist\cmd\PostRequestCmd;
use org\fktt\bstlist\util\logging\FileLogger;
use InvalidArgumentException;
use Exception;

/**
 * class handles remote user check against FreDL DB and file upload from datasheet editor
 */
class RemoteSheetUpload extends Frame
{
    private static $URL = "http://grischan.org/fredl/misc/rauth.php";

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     * @return RemoteSheetUpload
     */
    public function __construct()
    {
        parent::__construct();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['HTTP_USER_AGENT'] == 'Datasheet-Editor')
        {
            $user = \json_decode($_POST['other'], true);

            $log = new FileLogger("rauth.log");
            $log->open();
            $log->write("HTTP POST Request by user ".$user['name']);

            $response = 'false';
            $superUser = false;
            // Check request user is superuser
            global $dataDir;
            include($dataDir.'/data/_site/users.php');
            /** @var $users array */
            if (\array_key_exists($user['name'], $users))
            {
                $superUser = true;
                $log->write("Request user {$user['name']} is superuser");
                if ($users[$user['name']]['password'] === $user['pass'])
                {
                    $response = 'true';
                }
                else
                {
                    $response = 'false';
                }
            }
            // Check request user is module owner only if not superuser
            $userIsOwner = false;
            if (!$superUser)
            {
                $matches = array();
                \preg_match('/(?<Alpha>[a-zA-Z]+)(?<Numeric>[0-9]+)/', $user['modid'], $matches);
                // check user name e.g. MaMus to module identifier starting with the same initials
                if ($matches[1] == $user['name'])
                {
                    $userIsOwner = true;
                    $log->write("Request user {$user['name']} is module owner of {$user['modid']}");
                }
                else
                {
                    $response = "Error: Not owner of module!";
                }
            }
            // Check request user authentication against FreDL only if not superuser and if module owner
            if (!$superUser && $userIsOwner)
            {
                $log->write("Try connection to ".self::$URL);
                try
                {
                    $pr = new PostRequestCmd(self::$URL);
                    $pr->setHttpUserAgent("FreDL-RAR");
                    $pr->setBody("auth_data=".$_POST['check']);
                    $response = $pr->doCommand();
                }
                catch (Exception $e) // Invalid URL
                {
                    $response = $e->getMessage();
                }
            }
            if ($response == 'true')
            {
                $log->write("Authentication successful for user ".$user['name']);
                /* Aktionen duerfen ausgefuehrt werden
                 *
                 * Es koennen maximal zwei Dateien hochgeladen werden:
                 * 1.) Datenblatt
                 * 2.) Gleisplanbild
                 *
                 * Faelle: (Pruefung anhand des Kuerzels, im Verzeichnis muss Verzeichnis mit selben Namen existieren)
                 * Datenblatt existiert bereits (update) auf dem Server
                 *
                 *      Uebereinstimmung Kuerzel in FKTT100 (optional)
                 *      Nein
                 *          Warnung ausgeben
                 *          Vorgang fortsetzen
                 *      pruefen ob Gleisplan bereits existiert
                 *      Ja
                 *          wenn neuer als vorhanden => ersetzen
                 *      Sicherung vorhandenes Datenblatt anlegen
                 *      Datenblatt ersetzen
                 *
                 * Datenblatt existiert nicht auf dem Server
                 *      nachsehen in FKTT100, ob Kuerzel existiert
                 *      Nein
                 *          Abbruch mit Fehlermeldung => ohne gueltiges Kuerzel kein automatisches hochladen!
                 *      Ja
                 *          Anlegen neues Verzeichnis mit gueltigem Kuerzel aus FKTT100
                 *          Kopieren aller notwendigen Basisdateien (dtd,css,xsl)
                 *          Kopieren/Verschieben xml Datenblatt und Bilddatei (sofern vorhanden)
                 */
                $sheet = $_FILES['sheet'];
                if ($sheet['error'] == 0 && \is_uploaded_file($sheet['tmp_name']))
                {
                    $path = "db/".$user['path']."/";
                    $f = new File($path.$sheet['name']);
                    if ($f->exists())
                    {
                        // check FKTT100? would be nice but there is not one at the moment
                        if (\sizeof($_FILES) == 2)
                        {
                            $layout = $_FILES['layout'];
                            if ($layout['error'] == 0 && \is_uploaded_file($layout['tmp_name']))
                            {
                                $l = new File($path.$layout['name']);
                                // check file size? last mod?
                                if ($l->exists() && $l->getSize() != $layout['size'])
                                {
                                    $ext = $l->getExtension();
                                    \rename($l->getPathname(), $l->getParent()."/".$l->getBasename(".".$ext).\date("YmdHi").$ext.".old");
                                }
                                if (!$l->exists() || $l->getSize() != $layout['size'])
                                {
                                    // if file exists it is overwritten by uploaded one!
                                    \move_uploaded_file($layout['tmp_name'], $l->getPathname());
                                    $log->write("Update layout ".$layout['name']." successful");
                                }
                                else
                                {
                                    $log->write("Update layout {$layout['name']} not necessary");
                                }
                            }
                        }
                        // file must be always a xml file
                        \rename($f->getPathname(), $f->getParent()."/".$f->getBasename(".xml").\date("YmdHi")."xml.old");
                        \move_uploaded_file($sheet['tmp_name'], $f->getPathname());
                        $log->write("Update datasheet ".$sheet['name']." successful");
                        echo "Success";
                    }
                    else
                    {
                        // check FKTT100 on short
                        // The FKTT100 does not exist at the point of writing this code
                        // that's why this must fail in any case
                        $log->write("Adding new datasheet named ".$sheet['name']." not possible yet");
                        echo "Error: Adding a new datasheet not possible yet!";
                    }
                }
                else
                {
                    $log->write("Upload failed for datasheet ".$sheet['name']);
                    echo "Error: Upload failed!";
                }
        	}
            elseif ($response == 'false')
            {
                $log->write("Authentication failed for user ".$user['name']);
                echo "Error: Authentication failed";
            }
            else
            {
                // Other error(s)
                $log->write($response);
                echo $response;
            }
            $log->close();
            exit;
        }
        return $this;
    }

    protected function getCallableMethods()
    {
        return array();
    }
}

class FileUploadCmd
{

}