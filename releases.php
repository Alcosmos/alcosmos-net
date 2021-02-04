<?php
	/*\
	 * Lists the repositories from the given GitHub account along with some data about them
	 * Structured and formatted for alcosmos.ddns.net?tab=releases
	 *
	 * A part of alcosmos.ddns.net
	\*/
	
				$repos = file_get_contents('https://api.github.com/users/Alcosmos/repos'); # ('data/repos');
				
				if ($repos === FALSE) {
					echo '<p>
						Sorry, GitHub limits their API to only 5000 requests per hour, and my website has already reached that number.
					</p>
					<p>
						Just go straight to <a href="https://github.com/Alcosmos" target="_blank">my GitHub</a>.
					</p>';
					return;
				}
				
				$i = 1;
				
				foreach ($repos as $value) {
					print('
			<p class="title">
				' . $value['name'] . '
			</p>
			<p>
				<b>&nbsp;');
				
				if ($value['language'] != '') {
					print ($value['language'].', ');
				}
				
				if (isset($value['license']['name'])) {
					$license = json_decode(file_get_contents($value['license']['url']), true);
					
					if (isset($license['html_url'])) {
						print ('<a href="' . $license['html_url'] . '" target="_blank">' . $value['license']['name'] . '</a>');
					} else {
						print ($value['license']['name']);
					}
				} else {
					print ('public domain');
				}

				print('</a></b>
			</p>
			<p>
				' . $value['description'] . '
			</p>
			<p>
				<a href="' . $value['html_url'] . '" target="_blank">GitHub</a> | <a href="' . $value['html_url'] . '/archive/' . $value['default_branch'] . '.zip" target="_blank">Download</a>
			</p>');
					
					if ($i < count($repos)) {
						print('<hr>');
					}
					
					$i++;
				}
?>
