
      <h3>Addon Daten Verzeichnis als Zip sichern:</h3>
      <p>
        <form action="<?php echo $this->getFormActionUri(); ?>" method="post">
            <input type="hidden" name="cmd" value="export" />
            <input type="submit" value="Export starten" />
        </form>
      </p>
      <hr />
      <p class="klein">
         zuletzt ge&auml;ndert: <?php echo $this->getLastChangeTimestamp(); ?><br/>
        <a href="&#109;&#97;&#105;&#108;&#116;&#111;:seiste&#064;yahoo.de">
        Fragen und Klagen an Stefan Seibt</a>
      </p>
