<?php

/**
 * mssql view.
 *
 * @category   apps
 * @package    mssql
 * @subpackage views
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2014 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/mssql/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.  
//  
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// Load dependencies
///////////////////////////////////////////////////////////////////////////////

$this->lang->load('base');
$this->lang->load('mssql');

///////////////////////////////////////////////////////////////////////////////
// Show warning if password is not set
///////////////////////////////////////////////////////////////////////////////

echo "<div id='mssql_running' style='display:none;'>";
echo infobox_warning(lang('base_warning'), lang('mssql_management_tool_not_accessible'));
echo "</div>";

///////////////////////////////////////////////////////////////////////////////
// Password not set
///////////////////////////////////////////////////////////////////////////////

echo "<div id='mssql_no_password' style='display:none;'>";
echo infobox_warning(lang('base_warning'), lang('mssql_lang_please_set_a_database_password'));

echo form_open('mssql');
echo form_header(lang('base_password'));

echo field_password('new_password', '', lang('base_password'));
echo field_password('new_verify', '', lang('base_verify'));

echo field_button_set(
    array(form_submit_custom('submit_new', lang('mssql_set_password'), 'high'))
);

echo form_footer();
echo form_close();

echo "</div>";

///////////////////////////////////////////////////////////////////////////////
// Password set
///////////////////////////////////////////////////////////////////////////////

echo "<div id='mssql_password_ok' style='display:none;'>";

$options['buttons']  = array(
    anchor_custom($url_download, lang('mssql_go_to_download_link'), 'high', array('target' => '_blank')),
    anchor_custom('JavaScript:', 'Start', 'high', array('id' => 'btn_start_stop')),
);

echo infobox_highlight(
    lang('mssql_management_tool'),
    lang('mssql_management_tool_help'),
    $options
);

echo form_open('mssql',array('id' => 'mssql_password_set'));
echo form_header(lang('base_password'));

echo field_password('password', '', lang('base_password'));
echo field_password('verify', '', lang('base_verify'));
echo field_password('system_password', '', lang('mssql_system_password'));

// echo field_button_set(
//     array(form_submit_update('submit', 'high'))
// );
echo '<input type="submit" id="submit_btn" name="submit" value="Update" class="btn theme-form-submit-update  btn-primary" style="display:none">';
echo '<div class="btn-group"><button type="button" onclick="checkServerStatus(this)" class="btn theme-form-submit-update btn-primary">Update</button></div>';
echo form_footer();
echo form_close();

echo "</div>";

?>
<!-- Modal -->
<div id="confirm_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Password Reset</h4>
      </div>
      <div class="modal-body">
        <?php echo infobox_warning(lang('base_warning'), lang('mssql_management_tool_not_accessible')); ?>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="submitFormFinal(this)" class="btn btn-danger theme-form-submit-update">Stop & Change</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>

  </div>
</div>
