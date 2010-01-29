<?php


/**
 * CommandlineHelper class to easy work with command line scripts
 *
 * Usage pattern:
 *
 * $options = CommandlineHelper::microgetopt($argv, array(
 *	"diff" => "::", // option --diff which can carry an optional argument
 *	"applydict"=>":", // option --applydict which must carry a mandatory argument
 *	"help" => "" // option --help which must not have an argument
 * ));
 *
 */
class System_Commandline_Helper {

	/**
	 *
	 * Will parse command line arguments in long form.
	 * Takes argv and possible options arguments. Returns hash array of options populated.
	 *
	 * @param array $argv
	 * @param array $longopts
	 * @return array
	 */

	public static function microgetopt($argv,$longopts) {
		$options=array();
		while ($ar = array_shift($argv)) {
			if (self::isOption($ar)) {
				$key = self::extractOption($ar);
				if (!isset($longopts[$key])) {
					error_log(sprintf("Invalid option: %s.\n", $key));
					return false;
				}
				if ($longopts[$key] == ":") { // must be
					$prospect=array_shift($argv);
					if (is_null($prospect) or self::isOption($prospect)) {
						self::printOptionMustHaveArgument($key);
						return false;
					}
					$options[$key]=$prospect;

				} elseif ($longopts[$key]=='::') { // optional
					$options[$key]='';
					$prospect = array_shift($argv);
					if ($prospect == null) {
					} elseif (self::isOption($prospect)) {
						array_unshift($argv, $prospect);
					} else {
						$options[$key]=$prospect;
					}
				} elseif ($longopts[$key]=='') { // no arg
					$options[$key]="";
				}
			}
		}
		return $options;
	}
	private static function isOption($candidate) {
		return substr($candidate, 0, 2) == '--'?true:false;
	}
	private static function extractOption($argument) {
		return substr($argument,2,1000);
	}
	private static function printOptionMustHaveArgument($argument) {
		error_log(sprintf("Option must have an argument: %s\n", '--'.$argument));
	}
}
