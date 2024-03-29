<?php

namespace OE\includes\html;

use OE\includes\classes\Base_Tab;

settings_errors();

?>
<div class="oe-notification">
</div>
<?php

class Manage_Department extends Base_Tab
{
    public function __construct()
    {
        parent::__construct('Department Details', 'Create Department');

        $this->tab_body();
    }
    public function tab_panel1()
    {

?>
        <div id="tab-one-panel" class="panel active">
            <div style="overflow-x:auto;">
                <table>
                    <tr>
                        <th>Department Name</th>
                        <th>Date Created</th>
                        <th>Last Updated</th>
                        <th>Update</th>
                        <th>Delete</th>
                        <th>Author</th>
                    </tr>
                    <?php $this->info_table_rows(); ?>
                </table>
            </div>
        </div>
    <?php

    }

    public function tab_panel2()
    {

    ?>
        <div id="tab-two-panel" class="panel">
            <div class="wrap">
                <form id="department_form" action="" method='POST'>
                    <?php $this->dept_field() ?>
                    <?php submit_button('Create Department') ?>
                </form>
            </div>
        </div>
    <?php

    }
    public function info_table_rows()
    {
        if ($this->dept_data) {
            $zoneList = timezone_identifiers_list();
            if (in_array(wp_timezone_string(), $zoneList)) {
                date_default_timezone_set(wp_timezone_string());
            } else {
                date_default_timezone_set('America/Los_Angeles');
            }
            foreach ($this->dept_data as $data) {
                if ($data->dept_last_updated != 0) {
                    $last_updated = date("Y-m-d  h:i:sa", $data->dept_last_updated);
                } else {
                    $last_updated = "Not updated";
                }
                echo '<tr>
                            <td><input type="text" value="' . $data->dept_name . '"/></td>
                            <td>' . date("Y-m-d  h:i:sa", $data->dept_create_date) . '</td>
                            <td>' . $last_updated . '</td>
                            <td><button data-update-date="' . time() . '" data-action="update" id="' . $data->dept_create_date . '" class="oe-update oe-green">Update</button></td>
                            <td><button data-action="delete" id="' . $data->dept_create_date . '" class="oe-delete oe-red">Delete</button></td>
                            <td>' . get_userdata($data->dept_author)->data->user_nicename . '</td>
                    </tr>';
            }
        } else {
            return;
        }
    }
    public function dept_field()
    {

    ?>
        <div class="s_fields">
            <div class="single_field">
                <strong>
                    <label for="dept">Department Name:</label>
                </strong>
                <input type="text" name="dept_name" />
            </div>
            <input type="hidden" name="dept_create_date" value="<?php echo time(); ?>" />
            <input type="hidden" name="dept_author" value="<?php echo get_current_user_id(); ?>" />
        </div>
<?php

    }
}
