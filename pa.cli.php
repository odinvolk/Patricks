<?php

class ECli extends ExceptionTemplate {
	protected $_list = array(
			'ent_name0'		=> array(1, 'The provided string "{needle}" matches no entity'),
			'ent_nameN'		=> array(2, 'The provided string "{needle}" matches multiple entities'),
			);
	}

/** Match a PulseAudio entity type: one of self::$ENT_LIST
 * @param string	$name	Entity type string
 * @param bool		$prefix	Enable prefix-matching
 * @return string|null The full name. It's a property of PulseAudio
 */
function str_imatch($haystack, $needle, $partial = true){
	$needleL = strlen($needle);
	$candidates = array();
	// Find
	foreach ($haystack as $entname){
		$entnameL = strlen($entname);
		if ($needleL > $entnameL)
			continue;
		if (!$partial && $needleL != $entnameL)
			continue;
		$cmpL = min($needleL, $entnameL);
		if (strncasecmp($needle, $entname, $cmpL) === 0)
			$candidates[] = $entname;
		}
	// Return
	if (count($candidates) == 0){
		$candidates = $haystack;
		throw new ECli('ent_name0', compact('needle', 'candidates'));
		}
	if (count($candidates) > 1)
		throw new ECli('ent_nameN', compact('needle', 'candidates'));
	return array_shift($candidates);
	}