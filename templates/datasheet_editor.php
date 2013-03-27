
      <?php gpOutput::Area('Datasheet_Html_Area','<div class="datasheet_html_area">%s</div>'); ?>
      <?php gpOutput::GetArea('Datasheet_Html_Area',"
      <h3>Betriebsstellen-Datenblatt-Editor</h3>
      <p>
        Einige werden sich sicher noch an die erste M&ouml;glichkeit zur
        Erstellung von Datenbl&auml;ttern erinnern k&ouml;nnen. Das gro&szlig;e
        Formular zur Dateneingabe; sp&auml;ter dann auch zur Beabeitung.
      </p>
      <p>
        F&uuml;r den Anfang, sprich Schnellschu&szlig;, gen&uuml;gte das,
        stie&szlig; dann allerdings recht schnell an seine Grenzen. Das Formular,
        bestehend aus einer einzigen Seite, war un&uuml;bersichtlich, sehr lang
        und teilweise verwirrend. Ging etwas schief beim Abspeichern, waren die
        Daten einfach weg. F&uuml;r Benutzer ein unhaltbarer Umstand und meinem
        Anspruch gen&uuml;gte es sp&auml;ter dann auch nicht mehr.
      </p>
      <p>
        Ich begann zu &uuml;berlegen, wie und womit man es besser und vor allem
        Benutzerfreundlicher machen kann. Schlussendlich lief es auf eine
        Java-basierte L&ouml;sung hinaus, denn die Unterst&uuml;tzung und die
        vorhandene Basis waren der Schl&uuml;sselpunkt. Im November 2009 sind
        dann die ersten Programmteile entstanden.
      </p>
      <p>
        Jetzt, Ende April/Anfang Mai 2012, steht erstmals eine Beta-Version dieser
        Neuentwicklung zum Testen zur Verf&uuml;gung. Es ist sicherlich noch
        nicht alles perfekt, aber es k&ouml;nnen g&uuml;ltige Datenbl&auml;tter
        erzeugt und bearbeitet werden.
      </p>
      "); ?>
      <p>
        Dr&uuml;cke <?php echo $this->getJNLPFileUrl(); ?> um die Anwendung direkt zu starten.
      </p>
      <p>
        Alle Pakete sind mit einem 
        <?php echo $this->getCertificateFileUrl("öffentlichen Zertifikat"); ?>
        signiert, was bei Bedarf installiert werden kann.
      </p>
      <hr />
      <p class="klein">
         zuletzt ge&auml;ndert: <?php echo $this->getLastChangeTimestamp(); ?><br/>
        <a href="&#109;&#97;&#105;&#108;&#116;&#111;:seiste&#064;yahoo.de">
        Fragen und Klagen an Stefan Seibt</a>
      </p>

