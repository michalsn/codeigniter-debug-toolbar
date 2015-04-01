<?php defined('BASEPATH') or die('No direct script access.');

class debug_toolbar {

	// system.log events
	public $logs = array();

	public $benchmark_name = 'debug_toolbar';

	// show the toolbar
	public function render($print = false)
	{
		$CI =& get_instance();
		$CI->config->load('debug_toolbar');

		if (!$CI->config->item('render'))
		{
			if ((!isset($_GET['debug_key'])) || ($_GET['debug_key']!==$CI->config->item('debug_key')))
			{
				$CI->output->_display();
				return;
			}
		}

		$CI->benchmark->mark($this->benchmark_name);
		$CI->load->helper('number');

		$panels = $CI->config->item('panels');

		$data = array('panels' => $panels);

		if ($panels['database'])
			$data['queries'] = $this->queries();

		if ($panels['benchmarks'])
			$data['benchmarks'] = $this->benchmarks();

		if ($panels['files'])
			$data['files'] = $this->get_files();

		if ($panels['logs'])
			$data['logs'] = self::logs();

		//if ($panels['vars_and_config'])
			$data['configs'] = $this->configs();

		//if ($CI->config->item('firephp_enabled'))
		//	$this->firephp();

		/*$align = '';
		switch ($CI->config->item('align'))
		{
			case 'right':
			$align = 'right: 0';
			break;
			case 'center':
			$align = '';
			break;
			default:
			$align = 'left: 0';
		}
		$data['align'] = $align;*/
		$data['styles'] = read_file(FCPATH.$CI->config->item('css_path').'/debug_toolbar.css');
		$data['scripts'] = read_file(FCPATH.$CI->config->item('js_path').'/debug_toolbar.js');
		if ($CI->output->get_output())
		{
			/*
			 * Inject toolbar html before </body> tag.  If there is
			 * no closing body tag, I dont know what to do :P
			 */
			$CI->output->set_output(preg_replace('/<\/body>/', $CI->load->view('debug_toolbar', $data, TRUE). '</body>', $CI->output->get_output()));
		}
		//else
		//{
		//	$CI->load->view('debug_toolbar', $data); //$template->render($print);
		//}

		$CI->output->_display();

		$CI->benchmark->mark($this->benchmark_name);
	}

	public function get_files()
	{
		$files = (array)get_included_files();
		sort($files);
		return $files;
	}

	public function logs()
	{
		$LOG =& load_class('Log');
		return $LOG->logs;
	}

	public function queries()
	{
		$CI =& get_instance();
		$res = array();
		if (isset($CI->db))
		{
			foreach ($CI->db->queries as $idx => $query)
				$res[] = array(
				               'query' => $query,
				               'time'	=> isset($CI->db->query_times[$idx]) ? $CI->db->query_times[$idx] : 0,
				               'rows'	=> -1
				               );
		}
		return $res;
	}





	public function benchmarks()
	{
		$CI =& get_instance();
		$CI->benchmark->mark("debug_toolbar_end");
		$benchmarks = array();
		foreach ($CI->benchmark->checkpoints as $name => $cp)
		{
			$benchmarks[$name] = array(
			                           'name'   => ucwords(str_replace(array('_', '-'), ' ', $name)),
			                           'diff'   => $cp['diff'],
			                           'memory' => $cp['memory']
			                           );
		}
		// $benchmarks = array_slice($benchmarks, 1) + array_slice($benchmarks, 0, 1);
		return $benchmarks;
	}

	/*
	 * Add toolbar data to FirePHP console
	 */
	private function firephp()
	{
		$CI =& get_instance();
		$CI->load->library('FirePHP');
		$firephp = $CI->firephp;

		$firephp->fb('CodeIgniter DEBUG TOOLBAR:');

		// globals

		$globals = array(
		                 'Post'    => empty($_POST)    ? array() : $_POST,
		                 'Get'     => empty($_GET)     ? array() : $_GET,
		                 'Cookie'  => empty($_COOKIE)  ? array() : $_COOKIE,
		                 'Session' => empty($_SESSION) ? array() : $_SESSION
		                 );

		foreach ($globals as $name => $global)
		{
			$table = array();
			$table[] = array($name,'Value');

			foreach($global as $key => $value)
			{
				if (is_object($value))
					$value = get_class($value).' [object]';

				$table[] = array($key, $value);
			}

			$firephp->fb(
			             array(
			                   "$name: ".count($global).' variables',
			                   $table
			                   ),
			             FirePHP::TABLE
			             );
		}

		// database

		$queries = self::queries();

		$total_time = $total_rows = 0;
		$table = array();
		$table[] = array('SQL Statement','Time','Rows');

		foreach ($queries as $query)
		{
			$table[] = array(
			                 str_replace("\n",' ',$query['query']),
			                 number_format($query['time'], 3),
			                 $query['rows']
			                 );

			$total_time += $query['time'];
			$total_rows += $query['rows'];
		}

		$firephp->fb(
		             array(
		                   'Queries: ' . count($queries).' SQL queries took '.number_format($total_time,3).' seconds and returned '.$total_rows.' rows',
		                   $table
		                   ),
		             FirePHP::TABLE
		             );

		// benchmarks

		$benchmarks = self::benchmarks();

		$table = array();
		$table[] = array('Benchmark','Time','Memory');

		foreach ($benchmarks as $name => $benchmark)
		{
			// Clean unique id from system benchmark names
			$name = ucwords(str_replace(array('_', '-'), ' ', $name));

			$table[] = array(
			                 $name,
			                 number_format($benchmark['time'], 3),
			                 number_format($benchmark['memory'] / 1024 / 1024, 2).'MB'
			                 );
		}

		$firephp->fb(
		             array(
		                   'Benchmarks: ' . count($benchmarks).' benchmarks took '.number_format($benchmark['time'], 3).' seconds and used up '. number_format($benchmark['memory'] / 1024 / 1024, 2).'MB'.' memory',
		                   $table
		                   ),
		             FirePHP::TABLE
		             );
	}

	/*
	 * Config is only directly accessible from inside
	 * the Kohana core class.  So, unfortunately, I have
	 * to go through and load every config file manually.
	 * This is pretty inneficient but I can't think of a way
	 * around it.
	 */
	private function configs()
	{
		$CI =& get_instance();
		$CI->load->helper(array('path', 'file'));

		if ($CI->config->item('skip_configs') === true)
			return array();

		// paths to application and system config
		$paths = array(
		               APPPATH.'config/'
		               );

		$configuration = array();
		$config=array();

		// matchbox support
		foreach ($paths as $path)
		{
			if (file_exists($path.'matchbox.php'))
			{
				$CI->load->config('matchbox');
				foreach ($CI->config->item('paths') as $matchboxpath)
				{
					if ($handle = opendir($matchboxpath))
					{
						while (($filename = readdir($handle)) !== false)
						{
							if (($filename!=='.') && ($filename!=='..') && (is_dir($matchboxpath.'/'.$filename)))
							{
								if (is_dir($matchboxpath.'/'.$filename.'/config'))
								{
									$mconfig='modules:'.$filename.'/';
									if ($modhandle = opendir($matchboxpath.'/'.$filename.'/config'))
									{
										while (($conffilename = readdir($modhandle)) !== false)
										{
											if (!is_dir($conffilename))
											{
												$file = $this->_strip_ext($conffilename);
												$mfile=$mconfig.$file;
												$config=$this->new_parse_ini($matchboxpath.'/'.$filename.'/config/'.$conffilename);
												if (isset($config) AND is_array($config))
												{
													$configuration[$mfile] = isset($configuration[$mfile]) ? array_merge($configuration[$mfile], $config) : $config;
												}
												$config = array();
											}
										}
									}
									closedir($modhandle);
								}
							}
						}
						closedir($handle);
					}
				}
			}
		}

		$skip = array_merge(array('constants'), $CI->config->item('skip_configs'));

		// load and merge configs in each path
		foreach ($paths as $path)
		{
			if ($handle = opendir($path))
			{
				// read all files in config dir
				while (($filename = readdir($handle)) !== false)
				{
					$file = $this->_strip_ext($filename);
					// remove file extension from file name
					$filename = $path . $filename;// $this->_strip_ext($file);

					// filter skip configs
					if (in_array($file, $skip) || (substr($filename, -4) != '.php'))
					{
						continue;
					}
					if (file_exists($filename) && !is_dir($filename))
					{
						$config=$this->new_parse_ini($filename);
						if (isset($config) AND is_array($config))
						{
							$configuration[$file] = isset($configuration[$file]) ? array_merge($configuration[$file], $config) : $config;
						}
						$config = array();
					}
				}
			}
			closedir($handle);
		}
		return $configuration;
	}

	// return a filename without extension
	private function _strip_ext($filename)
	{
		if (($pos = strrpos($filename, '.')) !== false)
		{
			return substr($filename, 0, $pos);
		}
		else
		{
			return $filename;
		}
	}

	// parse a config file - hackjob
	private function new_parse_ini($f)
	{
		// if cannot open file, return false
		if (!is_file($f))
			return false;

		$ini = file($f);

		$cats = array();

		// needs massive cleanup
		foreach ($ini as $i)
		{
			if (@preg_match('/(.+)=(.+)/', $i, $matches))
			{
				$field=str_replace("'","",trim($matches[1],"$ \t \'"));
				$field=str_replace("\t"," ",$field);
				$field=preg_replace('/\s\s+/', ' ', $field);
				$value=str_replace("'",'"',trim($matches[2],"; \t "));
				$value=str_replace("\t"," ",$value);
				$value=preg_replace('/\s\s+/', ' ', $value);
				if ($value[0]=='>')
					$value="=".$value;
				$value=ltrim(rtrim($value,'"'),'"');
				if (($field[0]!=='*')&&($field[0]!=='|')&&($field[0]!=='/'))
				{
					$cats[$field] = $value;
				}
			}
		}
		return $cats;
	}

}
