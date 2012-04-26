
      <h1>Die Betriebstellen des FKTT und ihre Daten</h1>
      <form action="<?php echo $this->getFormActionUri(); ?>" method="post">
      <table border="0">
          <tr>
              <td style="text-align: right;">Ordnen nach:&nbsp;</td>
              <td><select name="order" size="1"><?php echo $this->getOrderOptionsUI(); ?></select></td>
          </tr>
          <tr>
              <td style="text-align: right;">Epoche:&nbsp;</td>
              <td><select name="epoch" size="1" style="width:45px;"><?php echo $this->getEpochOptionsUI(); ?></select></td>
          </tr>
          <tr>
              <td style="text-align: right;"><input type="submit" name="startFilter" value="Start" /></td>
              <td style="text-align: right;"><input type="submit" value="Filter l&ouml;schen" name="reset" /></td>
          </tr>
      </table>
<!--        <style type="text/css">
        /* <![CDATA[ */
            table { border:1px solid; padding:2px; margin:4px; border-style:outset; }
            th, td { border:1px solid; padding:2px; margin:4px; border-style:inset; }
            td.mittig { text-align:center; }
        /* ]]> */
        </style>
      <table>-->
        <!-- BSTLIST_ENTRY_FOR_FKTT_PORTAL -->
<?php echo $this->getTable(); ?>
        <!-- BSTLIST_FLUSH_FOR_FKTT_PORTAL -->
<!--      </table>-->
      </form>
      <p><?php echo $this->getYellowPageLink(); ?></p>
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
         zuletzt ge&auml;ndert: <?php echo $this->getLastChangeTimestamp(); ?><br/>
        <a href="&#109;&#97;&#105;&#108;&#116;&#111;:seiste&#064;yahoo.de">
        Fragen und Klagen an Stefan Seibt</a></p>
      <?php gpOutput::Get('Extra','Test'); ?>
