
      <h1>Die Betriebstellen des FKTT und ihre Daten</h1>
        <!-- BSTLIST_ENTRY_FOR_FKTT_PORTAL -->
        <style type="text/css">
        /* <![CDATA[ */
            table { border:1px solid; padding:2px; margin:4px; border-style:outset; }
            th, td { border:1px solid; padding:2px; margin:4px; border-style:inset; }
            td.mittig { text-align:center; }
        /* ]]> */
        </style>
      <table>
         <tr>
            <td>Lfd. Nr.</td>
            <td>Betriebsstellenname</td>
            <td>K&uuml;rzel</td>
            <td>Kategorie</td>
            <td>Letzte &Auml;nderung</td>
         </tr>
         <?php $this->getTableEntries(); ?>
      </table>
        <!-- BSTLIST_FLUSH_FOR_FKTT_PORTAL -->
      <p>
         Achtung! Die Betriebsstellendateien sind XML-Dateien<br/>
         und nur mit folgenden Browsern ansehbar:<br />
         Internet Explorer ab Version 5,
         Opera ab Version 5.12,<br/>
         Netscape ab Version 6,
         Mozilla Firefox ab Version 1.0
      </p>
      <!--<p><?php $p = StationDatasheetSettings::getInstance()->newSheet(); /*$p['link']*/ echo common::Link(common::WhichPage(), $p['label'], 'cmd=add_new_sheet'); ?></p>-->
      <p>F&uuml;r die Zukunft ist geplant, die Seiten auch als HTML<br/> 
         bzw. XHTML abzulegen und die Fahrplaner und BFO<br/> 
         Ersteller werden das ganze auch noch im pdf Format<br/> bekommen.
      </p>
      <hr />
      <p class="klein">
         zuletzt ge&auml;ndert: <?php echo StationDatasheetSettings::getInstance()->lastAddonChange(); ?><br/>
        <a href="&#109;&#97;&#105;&#108;&#116;&#111;:seiste&#064;yahoo.de">
        Fragen und Klagen an Stefan Seibt</a></p>
      <?php gpOutput::Get('Extra','Test'); ?>
