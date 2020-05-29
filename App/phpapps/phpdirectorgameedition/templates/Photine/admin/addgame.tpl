{include file="admin_header.tpl"}
<center>
{$message}
<h2 align="center">{$LAN_126}</h2>
<form method="post" action="addgame.php">
<input type="hidden" value="add">
{$LAN_33} :<br />
<input type="text" name="name"><br />
{$LAN_118} :<br />
<input type="text" name="authorlink"><br />
{$LAN_34} :<br />
<input type="text" name="author"><br />
{$LAN_35} :<br />
<textarea name="description"></textarea><br />
{$LAN_127} :<br />
<input type="text" name="gameurl"><br />
{$LAN_128} :<br />
<input type="text" name="thumburl"><br />
{$LAN_131} :<br />
<select name="leaderboard">
<option value="0">{$LAN_132}</option>
<option value="1">{$LAN_133}</option>
</select><br />
{$LAN_129} :<br />
<input type="text" name="width"><br />
{$LAN_130} :<br />
<input type="text" name="height"><br />
<input type="submit" value="{$LAN_126}">
</form>
</center>
</body>
</html>
