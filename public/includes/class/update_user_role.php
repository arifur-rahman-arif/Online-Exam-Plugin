<?php
class OE_Update_user
{
    public function __construct()
    {
        add_action('profile_update', [$this, 'update_teacher_role']);
        add_action('profile_update', [$this, 'update_student_role']);
    }

    /**
     * @method is a callback fucntion for profile_update hook and going to update teacher
     * @param int $user_id
     * @return void
     */
    public function update_teacher_role($user_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'teacher';
        $res = $wpdb->get_results("SELECT * FROM " . $table . " WHERE teacher_id=" . $user_id . "");
        if (!$res) {
            return;
        }
        // if user role is changed to either a teacher or admin then the database will be updated accordingly
        if (get_userdata($user_id)->roles[0] == 'teacher' || get_userdata($user_id)->roles[0] == 'administrator') {
            self::teacher_update($res, $table, $user_id, false, true, 0);
        }
        // if user is changed to any other role except teacher or admin then the user user will be restricted
        else {
            self::teacher_update($res, $table, $user_id, true, false, time());
        }
    }

    /**
     * @method is a callback fucntion for profile_update hook and going to update teacher
     * @param int $user_id
     * @return void
     */
    public function update_student_role($user_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'students';
        $res = $wpdb->get_results("SELECT * FROM " . $table . " WHERE std_id=" . $user_id . "");
        if (!$res) {
            return;
        }
        // if user role is changed to either a student or admin then the database will be updated accordingly
        if (get_userdata($user_id)->roles[0] == 'student' || get_userdata($user_id)->roles[0] == 'administrator') {
            self::std_update($res, $table, $user_id, false, true, 0);
        }

        // if user is changed to any other role except student or admin then the user user will be restricted
        else {
            self::std_update($res, $table, $user_id, true, false, time());
        }

    }

    /**
     * @method is going to update teacher from wp_teacher table
     * @param bool $res  @param string $table @param int $user_id @param bool $restriction @param bool $status @param int|date $restrict_date
     * @return void
     */
    public static function teacher_update($res, $table, $user_id, $restriction, $status, $restrict_date)
    {
        global $wpdb;
        $update_res = $wpdb->update(
            $table,
            [
                'teacher_email' => sanitize_text_field(' ' . get_userdata($user_id)->data->user_email . ' '),
                'teacher_name' => sanitize_text_field(' ' . get_userdata($user_id)->data->display_name . ' '),
                'restriction' => $restriction,
                'status' => $status,
                'restrict_date' => $restrict_date,
            ],
            [
                'teacher_id' => $user_id,
            ],
            [
                '%s',
                '%s',
                '%d',
                '%d',
                '%d',
            ],
            [
                '%d',
            ],
        );
        /* if teacher is updated from wp_teacher table then update teacher from teacher cpt */
        if ($update_res) {
            self::update_teacher_post($user_id);
        }
    }

    /**
     * @method is going to update teacher from teacher cpt post type from wp_posts
     * @param int $post_id
     * @return void
     */
    public static function update_teacher_post($post_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'posts';
        $res = $wpdb->get_results("SELECT * FROM " . $table . " WHERE ID=" . $post_id . "");
        if ($res) {
            $update_res = $wpdb->update(
                $table,
                [
                    'post_title' => '' . get_userdata($post_id)->data->display_name . '',
                ],
                [
                    'ID' => $post_id,
                ],
                [
                    '%s',
                ],
                [
                    '%d',
                ],
            );
        }
    }

    /**
     * @method is going to update student from wp_students table
     * @param bool $res  @param string $table @param int $user_id @param bool $restriction @param bool $status @param int|date $restrict_date
     * @return void
     */
    public static function std_update($res, $table, $user_id, $restriction, $status, $restrict_date)
    {
        global $wpdb;
        $wpdb->update(
            $table,
            [
                'std_email' => sanitize_text_field(' ' . get_userdata($user_id)->data->user_email . ' '),
                'std_name' => sanitize_text_field(' ' . get_userdata($user_id)->data->display_name . ' '),
                'restriction' => $restriction,
                'status' => $status,
                'restrict_date' => $restrict_date,
            ],
            [
                'std_id' => $user_id,
            ],
            [
                '%s',
                '%s',
                '%d',
                '%d',
                '%d',
            ],
            [
                '%d',
            ],
        );
    }
}
new OE_Update_user();
