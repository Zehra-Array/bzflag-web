<?
include "../document.php";
include "useradmin.php";

$doc = new Document;
$doc->begin("user list", 1);
if(isset($bzID)) {
  $substr = explode("|", $bzID);
  $result = mysql_query("SELECT * FROM passwd WHERE password = '$substr[1]' AND username = '$substr[0]' AND access = '$substr[2]'");
  if(mysql_num_rows($result) == 1 && $substr[2] >= 3) {
    $result = mysql_query("SELECT * FROM passwd");
print <<< end
Access levels:<br>
end;
print_accesslevel(1);
print "<br>\n";
print_accesslevel(2);
print "<br>\n";
print_accesslevel(3);
print "<br>\n";
print <<< end
<br><br>
<table border="1">
  <tr><td>Username:</td><td>Access level:</td></tr>
end;
    while($currow = mysql_fetch_array($result)) {
      printf("  <tr><td>%s</td><td>%s</td></tr>\n", $currow["username"], $currow["access"]);
    }
print <<< end
</table>
end;
  } else {
print <<< end
Access denied.
end;
  }
} else {
print <<< end
Access denied.
end;
}

?>
