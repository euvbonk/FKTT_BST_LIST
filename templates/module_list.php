
    <h2>Modulreihungsliste erstellen</h2>
    <div style="position: absolute;">
        Daten hier einf&uuml;gen:
        <form action="<?php echo $this->getFormActionUri(); ?>" method="post">
            <textarea name="content" cols="40" rows="20"><?php echo $this->getPostContent(); ?></textarea>
            <br/>
            <input type="hidden" name="cmd" value="create" />
            <input type="reset" value="Textfeld leeren" />
            <input type="submit" value="Astliste erstellen" />
        </form>
    </div>
    <div style="position:relative; left:45%; width:400px;">
        <h3>Allgemeiner Hinweis:</h3>
        <p>
            Dieses Skript funktioniert nur, wenn die Modulzeichnungen im
            wesentlichen dem <a href="http://www.fremo-net.eu">Fremo</a>-Standard entsprechen! Ansonsten
            ergibt sich nur <a href="http://de.wikipedia.org/wiki/Kauderwelsch">Kauderwelsch</a>!
            <!--<a href="http://layer.uci.agh.edu.pl/~mczapkie/Train/tmp/modele/FREMO/Meetings/Modulzeichnungen%20mit%20AutoCAD%20050615.doc">als DOC</a>
            <a href="http://www.freewebs.com/lokomochka/2.3%20FREMO%20-%20Modulen/FREMO%20H0%20modulsymboler.pdf">HP 1 - PDF</a>-->
        </p>
        <h4>Anleitung f&uuml;r <a href="http://de.wikipedia.org/wiki/AutoCAD">ACAD</a> 2002</h4>
        <p>
            Zuerst alle betreffenden Module der Reihe nach markieren 
            (STRG-Taste gedr&uuml;ckt halten und mit linker Maustaste Module ausw&auml;hlen).
            Dann den Befehl &quot;LISTE&quot; (DE-Version) bzw. &quot;LIST&quot; (EN-Version) ausf&uuml;hren.
            Notwendiges dr&uuml;cken der Entertaste durchf&uuml;hren und
            die Ausgabe des Befehls in das linke Fenster kopieren.
        </p>
        <p>
            Sollte das Fenster &quot;&uuml;berlaufen&quot;, sprich die gesamte Ausgabe des
            Befehls dort nicht hineinpassen, so kann man sich die Ausgabe
            aus dem <a href="http://de.wikipedia.org/wiki/Logdatei">Logfile</a> holen.<br/>
            Dazu geht man (bei der EN-Version) &uuml;ber &quot;Tools &rarr; Options&quot; oder
            &quot;F2 &rarr; Edit &rarr; Options&quot; in den Reiter &quot;Files&quot; und sucht im Fenster den
            Eintrag &quot;Log File Location&quot;.<br/>
            Die Datei besteht aus dem Namen der gerade ge&ouml;ffneten Datei mit
            ein wenig Anh&auml;ngsel.
        </p>
    </div>
    <div style="position:relative; bottom:-10%; width:97%;">
      <hr />
      <p class="klein">
         zuletzt ge&auml;ndert: <?php echo $this->getLastChangeTimestamp(); ?><br/>
        <a href="&#109;&#97;&#105;&#108;&#116;&#111;:seiste&#064;yahoo.de">
        Fragen und Klagen an Stefan Seibt</a>
      </p>
    </div>
