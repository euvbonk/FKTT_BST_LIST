
    <h2>Frachtverkehr (grundlegende Berechnungen)</h2>
    <form action="<?php echo $this->getFormActionUri(); ?>" method="post">
    <table border="0">
        <tr>
            <td style="text-align: right;" rowspan="3">Tage pro Woche:&nbsp;</td>
            <td style="text-align: right;">5</td>
            <td><input type="radio" name="daysOfWeek" value="5.0" <?php echo ($this->getDaysOfWeek()==5.0) ? "checked=\"checked\"" : ""; ?>/></td>
            <td rowspan="3"><input type="hidden" name="cmd" value="calculate" /></td>
            <td rowspan="3" colspan="2">Mittlere L&auml;nge pro Wagen in cm:</td>
            <td rowspan="3" colspan="2"><input type="text" name="lengthPerCar" size="4" maxlength="4" value="<?php echo $this->getLengthPerCar(); ?>"/></td>
        </tr>
        <tr>
            <td style="text-align: right;">5,5</td>
            <td><input type="radio" name="daysOfWeek" value="5.5" <?php echo ($this->getDaysOfWeek()==5.5) ? "checked=\"checked\"" : ""; ?>/></td>
        </tr>
        <tr>
            <td style="text-align: right;">7</td>
            <td><input type="radio" name="daysOfWeek" value="7.0" <?php echo ($this->getDaysOfWeek()==7.0) ? "checked=\"checked\"" : ""; ?>/></td>
        </tr>
        <tr>
            <td>Filter f&uuml;r Betriebsstellen:</td>
            <td colspan="5"><input type="text" name="filterCSV" size="30" maxlength="40" value="<?php echo $this->getFilterCSV(); ?>"/></td>
            <td><input type="submit" value="Start" name="calculate" /></td>
            <td><input type="submit" value="Filter l&ouml;schen" name="reset" /></td>
        </tr>
    </table>
    <table cellspacing="1">
        <thead>
        <tr bgcolor="#C0C0C0">
            <th rowspan="2">X</th>
            <th rowspan="2">K&uuml;rzel</th>
            <th rowspan="2">Name</th>
            <th colspan="3">Wagen pro Tag</th>
            <th colspan="2">Hauptgleisl&auml;nge in cm</th>
        </tr>
        <tr bgcolor="#C0C0C0" >
            <th>Eingang</th>
            <th>Ausgang</th>
            <th>Max</th>
            <th>k&uuml;rzestes</th>
            <th>l&auml;ngstes</th>
        </tr>
        </thead>
        <tbody>
        <?php echo $this->getTableEntries(); ?>
        </tbody>
        <tfoot>
        <tr bgcolor="#eeeeee">
            <td colspan="3" style="text-align:right;">&#8721;:&nbsp;</td>
            <?php echo $this->getTableFooter(); ?>
        </tr>
        </tfoot>
    </table>
    <p>Minimale Zuganzahl zum Abfahren aller Frachten in diesem Abschnitt: <?php echo $this->getMinTrainCount(); ?></p>
    </form>
    <p class="klein" style="position: relative; bottom: 1.5%; border-top: 1px solid gray; width: 97%; padding-top: 10px;">
       zuletzt ge&auml;ndert: <?php echo $this->getLastChangeTimestamp(); ?>
       <br/>
       <a href="&#109;&#97;&#105;&#108;&#116;&#111;:seiste&#064;yahoo.de">
       Fragen und Klagen an Stefan Seibt</a>
    </p>
