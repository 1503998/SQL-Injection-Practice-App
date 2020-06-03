<div class="headermid">

  <div class="header">
    <span class="headerfont">Search</span>
  </div>

<div class="content">

<form method="GET" action="index.php">

    <input type="hidden" name="action" value="getsearch"/>
    <input type="hidden" name="orderby" value="dateposted"/>

    <p align="left"><strong>Search For:</strong>&nbsp;<input type="text" name="searchquery" size="50" class="textbox">&nbsp;posted by user&nbsp;<input type="text" name="byuser" size="30" class="textbox">&nbsp;(optional)</p>
    <p align="left"><strong>Search In:</strong><br/><input type="radio" name="searchin" value="subject">Subject Only<br/>
    <input type="radio" name="searchin" value="message">Message Only<br/>
    <input type="radio" name="searchin" value="submess" checked>Subject and Message</p>

    <p align="center">
    <input type="submit" value="Search" class="button" />  <input type="reset" value="Clear Form" name="resetbutton" class="button" /></td>
    </p>

</form>

</div>

<div class="headerbot">
</div>

</div>