<?PHP

function JKDev_console_admin_page() {
	global $filepath, $pluginUrl;
	
	function listFiles($dirKey = null) {
		global $filepath, $themePath;
		$dir =  toAbsolutePath($dirKey);
		
		$ignore = [ 'dev-console-admin.php', 'dev-console-admin-script.js', 'dev-console.php', 'dev-console-settings.php', 'ace', 'jklogo.svg', 'dev-console-admin-style.css'];
		
		if ($handle = opendir($dir)) {
			$relativePath = $dirKey;
		
			while (false !== ($entry = readdir($handle))) {
				$absoluteFile = $dir.$entry;
				$relativeFile = $relativePath.$entry;
				if ($entry != "." && $entry != ".." && !in_array($entry, $ignore) && !is_dir($absoluteFile)) {
					echo "<a onclick=\"readFile('".$relativeFile."')\" class=\"mPointer\">".$entry."</a>&nbsp;&nbsp;&nbsp;";
				} else if(is_dir($absoluteFile) && $entry != ".") {
					echo "<a onclick=\"readDirectory('".$relativeFile."/')\" class=\"mPointer directory\">".$entry."</a>&nbsp;&nbsp;&nbsp;";
				}
			}
		closedir($handle);
		}
	}
	
	function toAliasPath($absolutePath) {
		global $filepath, $themePath;
		if(strpos($absolutePath, 'wp-content/plugins') !== false) {
			$retval = str_replace($filepath, 'plugin', $absolutePath);
		} else if(strpos($absolutePath, 'wp-content/themes') !== false ) {
			$retval = str_replace($themePath, 'theme', $absolutePath);
		}
		
		return $retval;
	}
	
	function toAbsolutePath($aliasPath) {
		global $filepath, $themePath;
		if(strpos( $aliasPath, 'plugin') !== false) {
			$retval = str_replace('plugin', $filepath, $aliasPath);
		} else if(strpos($aliasPath, 'theme') !== false ) {
			$retval = str_replace('theme', $themePath, $aliasPath);
		} else {
			$retval = "WRONG ALIAS";
		}
		return $retval;
	}

	
	//Save new data!
	if(isset($_POST['path']) && isset($_POST['data'])) {
		$filepath = $_POST['path'];
		echo $filepath . PHP_EOL . "<br />";
		$filepath = toAbsolutePath($filepath);
		echo $filepath . PHP_EOL;
		$data = stripslashes($_POST['data']);
		
		//save data
		$file = fopen($filepath, "w") or die("Unable to open file!");
		fwrite($file, $data);
		fclose($file);
		
		echo "Data saved!";
		die();
	}
	
	//Read file
	if(isset($_POST['readFile'])) {
	
		$relativePath = $_POST['readFile'];
		$filename = toAbsolutePath($relativePath);
		echo $relativePath . '###';
		$rFile = fopen($filename, "r") or die("Unable to open file!");
		echo fread($rFile,filesize($filename));
		//echo file_get_contents($filename);

		die();
	}
	
	//Read directory
	if(isset($_POST['directory'])) {
		$dir = $_POST['directory'];
		echo "Current directory: ";
		listFiles($dir);
		die();
	}
	
	
	?>
		<span id="JK-dev-console">
		<link rel="stylesheet" type="text/css" href="<?PHP echo $pluginUrl?>dev-console-admin-style.css">
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.js"></script>
		<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/themes/base/jquery-ui.css"/>
		<h1>JKDev Console</h1>
		<div id="save-notice-wrapper"><p id="save-notice" class="anim opacityZero">Data saved!</p></div>
		<p class="description">
			Use <span class="hotkey">CTRL</span> + <span class="hotkey">S</span> to save!
		</p>
		<p class="description">
			Text size: 	<a onclick="textSmaller()" class="mPointer">smaller</a>&nbsp;&nbsp;&nbsp;
						<a onclick="textLarger()" class="mPointer">LARGER</a>
		</p>
		<p class="description">
			Plugin files: 	<?PHP listFiles('plugin/'); ?>
		</p>
		<p class="description">
			Theme files: <?PHP listFiles('theme/'); ?>
		</p>
		<p id="directory" class="description">
			DIRECTORY GOES HERE
		</p>
		
		<div class="filename" style="display: none;">/new file</div>
		<div id="mainWindow" class="editorFrame">
			<h2 class="LangTitle">Editor</h2> <h2 class="filename">new file</h2> 
				<a onclick="renameFile();" class="mPointer">[rename]</a>
				<a onclick="deleteFile();" class="mPointer">[delete]</a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a onclick="newFile();" class="mPointer">[new file]</a>
			<div id="mainEditor" class="editorarea"></div>
			<button type="button" onclick="saveData();">Save</button>
		</div>
		<div id="serverResponse" class="clear"></div>
		
		
		<script src="<?PHP echo $pluginUrl ?>ace/src-min/ace.js" type="text/javascript" charset="utf-8"></script>
		<script src="<?PHP echo $pluginUrl ?>dev-console-admin-script.js" type="text/javascript" charset="utf-8"></script>
		</span>
	<?PHP
}
?>