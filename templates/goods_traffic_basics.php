
    <h2>Frachtverkehr (grundlegende Berechnungen)</h2>
    <form action="<?php $this->printValue('FormActionUri'); ?>" method="post">
    <table style="border:0;">
        <tr>
            <td style="text-align: right;">Tage pro Woche:&nbsp;</td>
            <td><select name="daysOfWeek" size="1"><?php $this->printValue('DaysOfWeekOptionsUI'); ?></select></td>
            <td style="text-align: right;"><input type="submit" value="Start" name="calculate" /></td>
        </tr>
        <tr>
            <td style="text-align: right;">Mittlere L&auml;nge pro Wagen in cm:&nbsp;</td>
            <td><input type="text" name="lengthPerCar" size="4" maxlength="4" value="<?php $this->printValue('LengthPerCar'); ?>"/></td>
            <td style="text-align: right;"><input type="submit" value="Filter l&ouml;schen" name="reset" /></td>
        </tr>
        <tr>
            <td style="text-align:right;">Epoche:&nbsp;</td>
            <td><select name="epoch" size="1" style="width:45px;"><?php $this->printValue('EpochOptionsUI'); ?></select></td>
            <td rowspan="1"><input type="hidden" name="cmd" value="calculate" /></td>
        </tr>
        <tr>
            <td style="text-align:right;">Filter f&uuml;r Betriebsstellen:&nbsp;</td>
            <td colspan="2"><input type="text" name="filterCSV" size="45" maxlength="50" value="<?php $this->printValue('FilterCSV'); ?>"/></td>
        </tr>
    </table>
    <?php $this->printValue('Table'); ?>
    <p>Minimale Zuganzahl zum Abfahren aller Frachten in diesem Abschnitt: <?php $this->printValue('MinTrainCount'); ?></p>
    </form>
    <p class="klein" style="position: relative; bottom: 1.5%; border-top: 1px solid gray; width: 97%; padding-top: 10px;">
       zuletzt ge&auml;ndert: <?php $this->printValue('LastChange'); ?>
       <br/>
       <a href="&#109;&#97;&#105;&#108;&#116;&#111;:seiste&#064;yahoo.de">
       Fragen und Klagen an Stefan Seibt</a>
    </p>
