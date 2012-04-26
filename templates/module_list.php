
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
            wesentlichen dem Fremo-Standard entsprechen! Ansonsten
            ergibt sich nur <a href="http://de.wikipedia.org/wiki/Kauderwelsch">Kauderwelsch</a>!
        </p>
        <h4>Anleitung f&uuml;r ACAD 2002</h4>
        <p>
            Zuerst alle betreffenden Module der Reihe nach markieren.
            Dann den Befehl &quot;LISTE&quot; (DE-Version) bzw. &quot;LIST&quot; (EN-Version) ausf&uuml;hren.
            Notwendiges dr&uuml;cken der Entertaste durchf&uuml;hren und
            die Ausgabe des Befehls in das linke Fenster kopieren.
        </p>
        <p>
            Sollte das Fenster &quot;&uuml;berlaufen&quot;, sprich die gesamte Ausgabe des
            Befehls dort nicht hineinpassen, so kann man sich die Ausgabe
            aus dem <a href="http://de.wikipedia.org/wiki/Logdatei">Logfile</a> holen.<br/>
            Dazu geht man (bei der EN-Version) &uuml;ber &quot;Tools &rarr; Options&quot; oder
            &quot;F2 &rarr; Edit &rarr; Options&quot; in den Tab &quot;Files&quot; und sucht im Fenster den
            Eintrag &quot;Log File Location&quot;.<br/>
            Das Logfile besteht aus dem Namen der gerade ge&ouml;ffneten Datei mit
            ein wenig Anh&auml;ngsel.
        </p>
    </div>
    <div style="position:absolute; bottom:2%; width:97%;">
      <hr />
      <p class="klein">
         zuletzt ge&auml;ndert: <?php echo $this->getLastChangeTimestamp(); ?><br/>
        <a href="&#109;&#97;&#105;&#108;&#116;&#111;:seiste&#064;yahoo.de">
        Fragen und Klagen an Stefan Seibt</a>
      </p>
    </div>
