<?php

if (!function_exists('calculate_attendance_status')) {
    /**
     * Calculates the attendance status based on class start time and 15-minute grace period.
     *
     * @param string $timeIn Timestamp or time string of the tap-in.
     * @param string $startTime Official class start time (H:i:s).
     * @return string 'on_time' or 'late'
     */
    function calculate_attendance_status($timeIn, $startTime)
    {
        $tapTime = strtotime(date('H:i:s', strtotime($timeIn)));
        $classTime = strtotime($startTime);
        $graceTimestamp = $classTime + (15 * 60); // 15 minutes in seconds

        return ($tapTime <= $graceTimestamp) ? 'on_time' : 'late';
    }
}

if (!function_exists('format_attendance_time')) {
    /**
     * Formats a datetime string into a user-friendly time.
     *
     * @param string|null $datetime
     * @return string
     */
    function format_attendance_time($datetime)
    {
        if (empty($datetime)) return '---';
        return date('h:i A', strtotime($datetime));
    }
}

if (!function_exists('get_status_badge_class')) {
    /**
     * Returns DaisyUI badge class based on status.
     *
     * @param string $status
     * @return string
     */
    function get_status_badge_class($status)
    {
        switch ($status) {
            case 'on_time': return 'badge-success';
            case 'late':    return 'badge-warning';
            case 'incomplete': return 'badge-error';
            case 'absent':  return 'badge-ghost';
            case 'manual':  return 'badge-info';
            default:        return 'badge-neutral';
        }
    }
}
