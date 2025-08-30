<?php

function datetimeToUnixTimestamp(?string $date_string, string $date_string_format, string $user_timezone): ?int {
	if (!empty($date_string)) {
		$date_local = DateTime::createFromFormat($date_string_format, $date_string, new DateTimeZone($user_timezone));
		if ($date_local) {
			return $date_local->getTimestamp();
		}
	}
	return null;
}

function unixtimeToDatetime(?string $unixtimestamp, string $user_timezone, string $format, string $default): string {
    // Format if it's a valid Unix timestamp
    if (!empty($unixtimestamp) && is_numeric($unixtimestamp)) {
        $date_utc = new DateTime('@' . $unixtimestamp);
        $date_utc->setTimezone(new DateTimeZone($user_timezone));
        return $date_utc->format($format);
    }

    return $default;
}

?>