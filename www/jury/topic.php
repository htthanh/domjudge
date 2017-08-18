<?php
/**
 * View topic details
 *
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */

require('init.php');
require(LIBWWWDIR . '/checkers.jury.php');

$id = getRequestID();
$title = ucfirst((empty($_GET['cmd']) ? '' : specialchars($_GET['cmd']) . ' ') .
                 'topic' . ($id ? ' '.specialchars(@$id) : ''));

if ( isset($_GET['cmd'] ) ) {
    $cmd = $_GET['cmd'];
} else {
	$refresh = array(
		'after' => 15,
		'url' => $pagename . '?id=' . urlencode($id) .
			(isset($_GET['restrict']) ? '&restrict=' . urlencode($_GET['restrict']) : ''),
	);
}

require(LIBWWWDIR . '/header.php');

if ( !empty($cmd) ):

    requireAdmin();

    echo "<h2>$title</h2>\n\n";

    echo addForm('edit.php');

    echo "<table>\n";

    if ( $cmd == 'edit' ) {
        $row = $DB->q('MAYBETUPLE SELECT * FROM topic WHERE topicid = %i', $id);
		if ( !$row ) error("Missing or invalid course id");

		echo "<tr><td>Topic ID:</td><td>" .
		    addHidden('keydata[0][topicid]', $row['topicid']) .
		    specialchars($row['topicid']) . "</td></tr>\n";
		    // "<tr><td>:</td><td class=\"username\">" .
		    // addHidden('keydata[0][username]', $row['username']) .
		    // specialchars($row['username']);
    } else {
        // echo "<tr><td><label for=\"data_0__login_\">Username:</label></td><td class=\"username\">";
        // echo addInput('data[0][username]', null, 8, 15, 'pattern="' . IDENTIFIER_CHARS . '+" title="Alphanumerics only" required');
    }
    echo "</td></tr>\n";

?>
<tr><td><label for="data_0__name_">Topic name:</label></td>
<td><?php echo addInput('data[0][topicname]', @$row['topicname'], 35, 255, 'required')?></td></tr>

<tr><td><label for="data_0__courseid_">Course:</label></td>
<td><?php
$cmap = $DB->q("KEYVALUETABLE SELECT courseid, coursename FROM course ORDER BY courseid");
echo addSelect('data[0][courseid]', $cmap, @$row['cid'], true);
?>
</td></tr>

<tr><td><label for="data_0__description_">Description:</label></td>
<td><?php echo addInput('data[0][description]', @$row['description'], 35, 255, 'required')?></td></tr>

</table>
<?php
echo addHidden('cmd', $cmd) .
    addHidden('table','topic') .
    addSubmit('Save') .
    addSubmit('Cancel', 'cancel', null, true, 'formnovalidate') .
    addEndForm();

require(LIBWWWDIR . '/footer.php');
exit;

endif;