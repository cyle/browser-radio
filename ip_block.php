<?php

function ipCIDRCheck ($IP, $CIDR) {
	list ($net, $mask) = split ("/", $CIDR);
	$ip_net = ip2long ($net);
	$ip_mask = ~((1 << (32 - $mask)) - 1);
	$ip_ip = ip2long ($IP);
	$ip_ip_net = $ip_ip & $ip_mask;
	return ($ip_ip_net == $ip_net);
}

$in_a_range = false;
foreach ($ip_range_restricts as $ip_restrict) {
	if (ipCIDRCheck($_SERVER['REMOTE_ADDR'], $ip_restrict)) {
		$in_a_range = true;
	}
}

if (!$in_a_range) {
	die('You can only access this radio on certain subnets, sorry, lol.');
}

?>