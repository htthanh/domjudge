<?php
/**
 * View content details
 *
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */

require('init.php');
require(LIBWWWDIR . '/checkers.jury.php');

$id = getRequestID();
$title = ucfirst((empty($_GET['cmd']) ? '' : specialchars($_GET['cmd']) . ' ') .
                 'course' . ($id ? ' '.specialchars(@$id) : ''));

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
        $row = $DB->q('MAYBETUPLE SELECT * FROM bg_course WHERE courseid = %i', $id);
		if ( !$row ) error("Missing or invalid course id");

		echo "<tr><td>Course ID:</td><td>" .
		    addHidden('keydata[0][courseid]', $row['courseid']) .
		    specialchars($row['courseid']) . "</td></tr>\n";
		    // "<tr><td>:</td><td class=\"username\">" .
		    // addHidden('keydata[0][username]', $row['username']) .
		    // specialchars($row['username']);
    } else {
        // echo "<tr><td><label for=\"data_0__login_\">Username:</label></td><td class=\"username\">";
        // echo addInput('data[0][username]', null, 8, 15, 'pattern="' . IDENTIFIER_CHARS . '+" title="Alphanumerics only" required');
    }
    echo "</td></tr>\n";

?>
<tr><td><label for="data_0__name_">Course name:</label></td>
<td><?php echo addInput('data[0][coursename]', @$row['coursename'], 35, 255, 'required')?></td></tr>

<tr><td><label for="data_0__categoryid_">Contest:</label></td>
<td><?php
$cmap = $DB->q("KEYVALUETABLE SELECT cid, name FROM contest ORDER BY cid");
echo addSelect('data[0][contestid]', $cmap, @$row['cid'], true);
?>
</td></tr>

<tr><td><label for="data_0__endtime_string_">Start date:</label></td>
<td><?php echo addInput('data[0][startdate_string]', @$row['startdate'], 30, 64, 'required pattern="' . $pattern_datetime . '"')?></td></tr>

<tr><td><label for="data_0__endtime_string_">End date:</label></td>
<td><?php echo addInput('data[0][enddate_string]', @$row['enddate'], 30, 64, 'required pattern="' . $pattern_datetime . '"')?></td></tr>

<tr><td><label for="data_0__categoryid_">Category:</label></td>
<td><?php
$cmap = $DB->q("KEYVALUETABLE SELECT catid, description FROM bg_course_category ORDER BY catid");
echo addSelect('data[0][catid]', $cmap, @$row['catid'], true);
?>
</td></tr>
</table>
<?php
echo addHidden('cmd', $cmd) .
    addHidden('table','bg_course') .
    addSubmit('Save') .
    addSubmit('Cancel', 'cancel', null, true, 'formnovalidate') .
    addEndForm();

require(LIBWWWDIR . '/footer.php');
exit;

endif;