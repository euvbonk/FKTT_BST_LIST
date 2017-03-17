
    <h3>Frachtverkehr (grundlegende Berechnungen)</h3>
    <form action="<?php $this->printValue('FormActionUri'); ?>" method="post">
    <table style="border:0;margin-bottom: 1%;">
        <tr>
            <td style="text-align: right;">Tage pro Woche:&nbsp;</td>
            <td><label><select name="daysOfWeek" size="1"><?php $this->printValue('DaysOfWeekOptionsUI'); ?></select></label></td>
            <td style="text-align: right;"><input type="submit" value="Start" name="calculate" /></td>
        </tr>
        <tr>
            <td style="text-align: right;">Mittlere L&auml;nge pro Wagen in cm:&nbsp;</td>
            <td><label><input type="text" name="lengthPerCar" size="4" maxlength="4" value="<?php $this->printValue('LengthPerCar'); ?>"/></label></td>
            <td style="text-align: right;"><input type="submit" value="Filter l&ouml;schen" name="reset" /></td>
        </tr>
        <tr>
            <td style="text-align:right;">Epoche:&nbsp;</td>
            <td><label><select name="epoch" size="1" style="width:45px;"><?php $this->printValue('EpochOptionsUI'); ?></select></label></td>
            <td rowspan="1"><input type="hidden" name="cmd" value="calculate" /></td>
        </tr>
        <tr>
            <td style="text-align:right;">Filter f&uuml;r Betriebsstellen:&nbsp;</td>
            <td colspan="2"><input type="text" name="filterCSV" size="45" maxlength="50" value="<?php $this->printValue('FilterCSV'); ?>"/></td>
        </tr>
    </table>
    <?php $this->printValue('Table'); ?>
    <p style="padding-top: 1%;">Minimale Zuganzahl zum Abfahren aller Frachten in diesem Abschnitt: <?php $this->printValue('MinTrainCount'); ?></p>
    </form>
    <hr />
    <p class="klein">
       zuletzt ge&auml;ndert: <?php $this->printValue('LastChange'); ?>
       <br/>
       <a href="&#109;&#97;&#105;&#108;&#116;&#111;:seiste&#064;yahoo.de">
       Fragen und Klagen an Stefan Seibt</a>
    </p>
