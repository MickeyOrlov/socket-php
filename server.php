<?php
	header('Content-Type: text/plain;');
	ob_implicit_flush(); 
	$address = 'localhost'; 
	$port = 4545;
	$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	socket_bind($sock, $address, $port);
	socket_listen($sock, 5);
	$i = 0;
	do {
		$msgsock = socket_accept($sock);
		$buf = socket_read($msgsock, 5000);
		$answer = substr($buf, 6, 8);
		$str = file_get_contents("response.html");

		$content = $answer . "200 OK\r\n";
		if ($answer == "HTTP/1.1" && ($i % 2 == 0)) {
			$content .= "Content-Type: text/html;";
			$i++;
		} else if ($answer == "HTTP/1.1" && ($i % 2 != 0)) {
			$content .= "Content-Type: text/plain;";
			$i++;
		}
		$content .= "charset=utf-8\n" . "\n" . $str;
		socket_write($msgsock, $content, strlen($content));
		socket_close($msgsock);
	} while(true);

?>