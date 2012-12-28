<?php
/**
 * Ihush Cli
 *
 * @category   Ihush
 * @package    Ihush_Cli
 * @author     James.Huang <huangjuanshi@snda.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

//require_once 'Hush/Db/Config.php';

/**
 * @package Ihush_Cli
 */
class Ihush_Cli_Sys extends Ihush_Cli
{
	public function __init ()
	{
		parent::__init();
		
		$this->init_sql_be = realpath(__ROOT . '/doc/sql/ihush_core.sql');
		$this->init_sql_fe = realpath(__ROOT . '/doc/sql/ihush_apps.sql');
	}
	
	public function helpAction ()
	{
		// command description
		$this->_printHeader();
		echo "hush sys init\n";
		echo "hush sys uplib\n";
	}
	
	public function initAction () 
	{
		echo 
<<<NOTICE

**********************************************************
* Start to initialize the Hush Framework                 *
**********************************************************

Please pay attention to this action !!!

Because you will do following things :

1. Check or download the related libraries.
2. Import original databases (Please make sure your current databases were already backuped).
3. Check all the runtime environment variables and directories.
4. Clean all caches and runtime data.

Are you sure to do all above things [Y/N] : 
NOTICE;
		
		// check user input
		$input = fgets(fopen("php://stdin", "r"));
		if (strcasecmp(trim($input), 'y')) {
			exit;
		}
		
		// upgrade libraries
		$zendDir = __COMM_LIB_DIR . DIRECTORY_SEPARATOR . 'Zend';
		if (!is_dir($zendDir)) {
			$this->uplibAction();
		}
		
		// import backend and frontend
		$import_cmd_be = str_replace(
			array('{PARAMS}', '{SQLFILE}'), 
			array($this->_getCmdParams(), $this->init_sql_be),
			__MYSQL_IMPORT_COMMAND);
		
		$import_cmd_fe = str_replace(
			array('{PARAMS}', '{SQLFILE}'), 
			array($this->_getCmdParams(), $this->init_sql_fe),
			__MYSQL_IMPORT_COMMAND);
		
		echo "\nRun Command : $import_cmd_be\n";
		system($import_cmd_be, $be_res);
		
		echo "Run Command : $import_cmd_fe\n";
		system($import_cmd_fe, $fe_res);
		
		if (!$be_res && !$be_res) {
			echo "Import database ok.\n";
		} else {
			echo "Import database failed.\n";
			exit;
		}
		
		// check dirs and configs
		system('hush check dirs');
		system('hush check configs');
		
		echo 
<<<NOTICE

**********************************************************
* Initialize successfully                                *
**********************************************************

Thank you for using Hush Framework !!!

NOTICE;
	}
	
	public function uplibAction ()
	{
		// see in etc/global.config.php
		$libDir = __COMM_LIB_DIR;
		if (!is_dir($libDir)) {
			mkdir($libDir, 0777, true);
		}
		
		require_once 'Hush/Util/Download.php';
		$down = new Hush_Util_Download();
		
		// download Zend Framework
		echo "\nInstalling Zend Framework ..\n";
		$downFile = 'http://hush-framework.googlecode.com/files/ZendFramework-1.10.2.zip';
		$saveFile = $libDir . 'ZendFramework-1.10.2.zip';
		$savePath = $libDir . '.';
		if ($down->download($downFile, $saveFile)) {
			echo "Extracting.. ";
			$zip = new ZipArchive;
			$zip->open($saveFile);
			$zip->extractTo($savePath);
			$zip->close();
			unset($zip);
			echo "Done!\n";
		}
		
		// download Phpdoc
		echo "\nInstalling Php Documentor ..\n";
		$downFile = 'http://hush-framework.googlecode.com/files/Phpdoc.zip';
		$saveFile = $libDir . 'Phpdoc.zip';
		$savePath = $libDir . '.';
		if ($down->download($downFile, $saveFile)) {
			echo "Extracting.. ";
			$zip = new ZipArchive;
			$zip->open($saveFile);
			$zip->extractTo($savePath);
			$zip->close();
			unset($zip);
			echo "Done!\n";
		}
		
		// download Smarty_2
		echo "\nInstalling Smarty 2.x ..\n";
		$downFile = 'http://hush-framework.googlecode.com/files/Smarty-2.6.25.zip';
		$saveFile = $libDir . 'Smarty-2.6.25.zip';
		$savePath = $libDir . '.';
		if ($down->download($downFile, $saveFile)) {
			echo "Extracting.. ";
			$zip = new ZipArchive;
			$zip->open($saveFile);
			$zip->extractTo($savePath);
			$zip->close();
			unset($zip);
			echo "Done!\n";
		}
		
		// download Smarty_3
		echo "\nInstalling Smarty 3.x ..\n";
		$downFile = 'http://hush-framework.googlecode.com/files/Smarty-3beta.zip';
		$saveFile = $libDir . 'Smarty-3beta.zip';
		$savePath = $libDir . '.';
		if ($down->download($downFile, $saveFile)) {
			echo "Extracting.. ";
			$zip = new ZipArchive;
			$zip->open($saveFile);
			$zip->extractTo($savePath);
			$zip->close();
			unset($zip);
			echo "Done!\n";
		}
		
		unset($down);
		return true;
	}
}
