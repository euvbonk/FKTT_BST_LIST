<?php
class BasicDirectory
{
	private $directoryName;
	
	public function __construct($dirname="")
	{
		$this->directoryName = $dirname;
	}
	
	public function setDirName($name)
	{
		$this->directoryName = $name;
	}

	public function getDirName()
	{
		return $this->directoryName;
	}

	public function changeDirRights($rights)
	{
		@chmod($this->directoryName, $rights);
	}

	/* modified function from php-manual */
 	public static function scanDirectories($base='', $allowext, $data=array())
	{

		if (!file_exists($base) || !is_dir($base)) return array();

		$array = array_diff(scandir($base), array('.', '..')); # remove . and .. from the array

		foreach ($array as $value)
		{ /* loop through the array at the level of the supplied $base */

			$ext = substr($value, strrpos($value, '.') + 1);
			if (is_dir($base."/".$value) && $value != "unsorted")
			{ /* if this is a directory */
				#$data[] = $base."/".$value.'/'; /* add it to the $data array */
				$data = self::scanDirectories($base."/".$value.'/', $allowext, $data); /* then make a recursive call with the
				current $value as the $base supplying the $data array to carry into the recursion */

			} elseif (is_file($base.$value) && in_array($ext, $allowext))
			{ /* else if the current $value is a file */
				$data[] = $base.$value; /* just add the current $value to the $data array */
			}
		}
	 
	  return $data; // return the $data array
	}
}
?>
