<?php
namespace org\fktt\bstlist\pages\remoteUpload;

\import('cmd_PostRequestCmd');
\import('cmd_RilConfigCmd');
\import('io_File');
\import('pages_Frame');
\import('util_PhpConfigFileUtils');
\import('util_logging_FileLogger');

use org\fktt\bstlist\beans\datasheet\FileManagerImpl;
use org\fktt\bstlist\cmd\PostRequestCmd;
use org\fktt\bstlist\cmd\RilConfigCmd;
use org\fktt\bstlist\io\File;
use org\fktt\bstlist\pages\Frame;
use org\fktt\bstlist\util\PhpConfigFileUtils;
use org\fktt\bstlist\util\logging\FileLogger;

/**
 * class handles remote user check against FreDL DB and file upload from datasheet editor
 */
class RemoteSheetUpload extends Frame
{
    private static $URL = null;
    private static $CONFIG_FILE_NAME = "FreDLConfig.php";

    /**
     * @throws \Exception
     * @return RemoteSheetUpload
     */
    public function __construct()
    {
        parent::__construct();

        $cf = new File(self::$CONFIG_FILE_NAME);
        if (!$cf->exists())
        {
           throw new \Exception("File not found (".$cf->getName().")");
        }
        self::$URL = PhpConfigFileUtils::getArrayFromFile(new File(self::$CONFIG_FILE_NAME))['url'];

        $c = new RilConfigCmd();
        ## check on updating RilFKTT.100, this is done always after the first day of a new month
        if ($c->checkUpdate())
        {
            $c->runUpdate();
        }
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
            // Check request user is module owner or representative against FreDL only if not superuser
            if (!$superUser)
            {
                $log->write("Try connection to ".self::$URL);
                try
                {
                    $pr = new PostRequestCmd(self::$URL);
                    $pr->setHttpUserAgent("FreDL-RAR");
                    $pr->setBody("auth_data=".$_POST['check']);
                    $response = $pr->doCommand();
                }
                catch (\Exception $e) // Invalid URL?
                {
                    $response = $e->getMessage();
                }
                switch ($response)
                {
                    case 'wrongPassAndOrId' :
                        $response = "false";
                        break;
                    case 'true' :
                        $matches = array();
                        \preg_match('/(?<Alpha>[a-zA-Z]+)(?<Numeric>[0-9]+)/', $user['modid'], $matches);
                        if ($matches[1] == $user['name'])
                        {
                            $log->write("Request user {$user['name']} is module owner of {$user['modid']}");
                        }
                        else if ($matches[1] != $user['name'])
                        {
                            $log->write("Request user {$user['name']} is module representative of {$user['modid']}");
                        }
                        $log->write(\print_r($matches, true));
                        break;
                    case 'false' :
                        $response = "Error: Not owner or representative of module!";
                        $log->write("Request user {$user['name']} is not owner or representative of module {$user['modid']}");
                        break;
                }
            }
            if ($response == 'true')
            {
                $log->write("Authentication successful for user or representative ".$user['name']);
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
                 *      Ist es ein Epochendatenblatt eines bereits bestehenden Datenblattes?
                 *      Ja
                 *          Kopieren/Verschieben xml Datenblatt und Bilddatei (sofern vorhanden) in entsprechendes
                 *          Unterverzeichnis!
                 *      Nein
                 *          nachsehen in FKTT100, ob Kuerzel existiert
                 *          Nein
                 *              Abbruch mit Fehlermeldung => ohne gueltiges Kuerzel kein automatisches hochladen!
                 *          Ja
                 *              Kopieren/Verschieben xml Datenblatt und Bilddatei (sofern vorhanden) in entsprechendes
                 *              Unterverzeichnis!
                 */
                $sheet = $_FILES['sheet'];
                if ($sheet['error'] == 0 && \is_uploaded_file($sheet['tmp_name']))
                {
                    // das ist auch irgendwie problematisch!, denn hier gilt auch dasselbe wie für
                    // die JSON Datei und den Dateipfad, kann man nicht so einfach aus dem Namen
                    // ableiten
                    $f = $this->check($user['station'], $user['mshort'], $sheet['name']);
                    if ($f != null)
                    {
                        // check FKTT100? would be nice but there is not one at the moment
                        if (\sizeof($_FILES) == 2)
                        {
                            $layout = $_FILES['layout'];
                            if ($layout['error'] == 0 && \is_uploaded_file($layout['tmp_name']))
                            {
                                // Wie soll das mit den Bildern gehandhabt werden? Eines fuer alle Epochen oder
                                // je Epoche eines benamt wie die Datenblaetter selbst?
                                // => Muessen die Besitzer selbst entscheiden, denn es geht hier nach Dateinamen
                                $l = new File($f->getParentFile()->getPathname()."/".$layout['name']);
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
                        if ($f->exists())
                        { // file must be always a xml file
                            \rename($f->getPathname(), $f->getParent()."/".$f->getBasename(".xml").\date("YmdHi")."xml.old");
                        }
                        \move_uploaded_file($sheet['tmp_name'], $f->getPathname());
                        $log->write("Update datasheet ".$sheet['name']." successful");
                        echo "Success";
                    }
                    else if (\array_key_exists(\strtolower($user['mshort']), $c->getAsArray()))
                    {
                        // Unterscheidung:
                        // Wenn ein neues Epochendatenblatt hochgeladen werden soll, dann muss wenigstens
                        // das fuer die Epoche VI vorhanden sein
                        // da braucht dann auch nichts weiter geprueft werden, weil alles bereits an Ort
                        // und Stelle ist und nur das Blatt hochgeschoben werden muss
                        //
                        // Andererseits muss bei voelliger Neuanlage auch ein Verzeichnis sowie die
                        // benoetigten XSL, DTD, CSS Dateien kopiert werden
                        //
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

    /**
     * Checks if given parameter are in stored json string and returns true if and only if all
     * are in json otherwise false
     *
     * @param $stationName  (string) Name of the station
     * @param $stationShort (string) Stations abbreviation
     * @param $xmlFileName  (string) relative path to stored place
     * @return File|null
     */
    private function check($stationName, $stationShort, $xmlFileName)
    {
        $f = new File("db/bst_list.json");
        $json = \json_decode(\file_get_contents($f->getRealPath()));
        foreach ($json as $value)
        {
            // check whether file name is directly in the list of existing datasheets
            // if so the value is returned in $tf
            $tf = \array_filter($value->epochs, function($val) use($xmlFileName)
            {
                return \strstr($val, $xmlFileName);
            });
            $p = \explode("-", \basename($xmlFileName, ".xml"));
            // the datasheet file exists at the server
            if ($value->name == $stationName && $value->abb == $stationShort && sizeof($tf) > 0)
            {
                return new File("db/".\array_shift($tf));
            }
            // For an existing datasheet of epoch four add datasheet for a new epoch which is one to six
            // the datasheet file is an epoch datasheet which does not exists, but the base datasheet (epoch 4) exists
            elseif ($value->name == $stationName && $value->abb == $stationShort && sizeof($tf) == 0
                && \sizeof($value->epochs) > 0 && \in_array($p[1], FileManagerImpl::$EPOCHS))
            {
                // determine the correct file path
                $fp = \explode("/", $value->epochs[0]);
                return new File("db/".$fp[0]."/".$xmlFileName);
            }
        }
        return null;
    }
}

class FileUploadCmd
{

}