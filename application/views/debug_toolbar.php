<?php defined('BASEPATH') or die('No direct script access.') ?>

<?php
	$this->load->helper(array('date', 'url', 'html', 'string', 'debug'));
?>

<style type="text/css">
<?php echo $styles ?>
</style>

<script type="text/javascript">
<?php echo $scripts ?>
</script>

<div id="codeigniter-debug-toolbar">

	<!-- toolbar -->
	<div id="debug-toolbar" style="<?php echo $align ?>">
		<!-- Kohana link -->
<!--		<?php echo anchor(
			"http://codeigniter.com", 
			img($this->config->item('icon_path').'/debug_toolbar_codeigniter.png'), 
			array('target' => '_blank')
		) ?>
-->		
		<ul class="menu">
			
			<!-- Codeigniter version -->
			<li>
				<?php echo anchor("http://codeigniter.com/", img($this->config->item('icon_path').'/debug_toolbar_codeigniter.png'),array('target' => '_blank')) ?>
				<?php echo anchor("http://codeigniter.com/", CI_VERSION, array('target' => '_blank')) ?>
			</li>
			
			<!-- Benchmarks -->
			<?php if ($panels['benchmarks']): ?>
				<li id="time" onclick="debugToolbar.show('debug-benchmarks'); return false;">
					<?php echo img($this->config->item('icon_path').'/debug_toolbar_time.png') ?>
					<?php echo $this->benchmark->elapsed_time() ?> s
				</li>
				<li id="memory" onclick="debugToolbar.show('debug-benchmarks'); return false;">
					<?php echo img($this->config->item('icon_path').'/debug_toolbar_memory.png') ?>
					<?php echo $this->benchmark->memory_usage() ?>
				</li>
			<?php endif ?>
			
			<!-- Queries -->
			<?php if ($panels['database']): ?>
				<li id="toggle-database" onclick="debugToolbar.show('debug-database'); return false;">
					<?php echo img($this->config->item('icon_path').'/debug_toolbar_database.png') ?>
					<?php echo isset($queries) ? count($queries) : 0 ?>
				</li>
			<?php endif ?>
			
			<!-- Vars and Config -->
			<?php if ($panels['vars_and_config']): ?>
				<li id="toggle-vars" onclick="debugToolbar.show('debug-vars'); return false;">
					<?php echo img($this->config->item('icon_path').'/debug_toolbar_config.png') ?>
					vars &amp; config
				</li>
			<?php endif ?>
			
			<!-- Logs -->
			<?php if ($panels['logs']): ?>
				<li id="toggle-log" onclick="debugToolbar.show('debug-log'); return false;">
					<?php echo img($this->config->item('icon_path').'/debug_toolbar_logs.png') ?>
					logs
				</li>
			<?php endif ?>
			
			<!-- Ajax -->
			<?php if ($panels['ajax']): ?>
				<li id="toggle-ajax" onclick="debugToolbar.show('debug-ajax'); return false;" style="display: none">
					<?php echo img($this->config->item('icon_path').'/debug_toolbar_ajax.png') ?>
					ajax (<span>0</span>)
				</li>
			<?php endif ?>
		

                        <!-- Files -->
                        <?php if ($panels['files']): ?>
                                <li id="toggle-files" onclick="debugToolbar.show('debug-files'); return false;">
                                        <?php echo img($this->config->item('icon_path').'/debug_toolbar_files.png') ?>
                                        files
                                </li>
                        <?php endif ?>
	
			<!-- Swap sides -->
			<li onclick="debugToolbar.swap(); return false;">
				<?php echo img($this->config->item('icon_path').'/debug_toolbar_text_align_left.png') ?>
			</li>
			
			<!-- Close -->
			<li class="last" onclick="debugToolbar.close(); return false;">
				<?php echo img($this->config->item('icon_path').'/debug_toolbar_close.png') ?>
			</li>
		</ul>
	</div>
	
	<!-- benchmarks -->
	<?php if ($panels['benchmarks']): ?>
		<div id="debug-benchmarks" class="top" style="display: none;">
			<h1>Benchmarks</h1>
			<table cellspacing="0" cellpadding="0">
				<tr>
					<th align="left">benchmark</th>
					<th align="right">time</th>
					<th align="right">total</th>
					<th align="right">memory</th>
				</tr>
				<?php if (count($benchmarks)): ?>
					<?php foreach ((array)$benchmarks as $benchmark): ?>
						<?php $total = isset($total) ? $total + $benchmark['diff'] : $benchmark['diff'] ?>
						<tr class="<?php echo alternator('odd','even')?>">
							<td align="left"><?php echo $benchmark['name'] ?></td>
							<td align="right"><?php echo sprintf('%.2f', $benchmark['diff'] * 1000) ?> ms</td>
							<td align="right"><?php echo sprintf('%.2f', $total * 1000) ?> ms</td>
							<td align="right"><?php echo byte_format($benchmark['memory']) ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr class="<?php echo text::alternate('odd','even') ?>">
						<td colspan="4">no benchmarks to display</td>
					</tr>
				<?php endif ?>
			</table>
		</div>
	<?php endif ?>
	
	<!-- database -->
	<?php if ($panels['database']): ?>
		<div id="debug-database" class="top" style="display: none;">
			<h1>SQL  queries</h1>
			<table cellspacing="0" cellpadding="0">
				<tr align="left">
					<th>#</th>
					<th>query</th>
					<th>time</th>
					<th>rows</th>
				</tr>
				<?php $total_time = $total_rows = 0;
							foreach ((array)$queries as $id => $query):
				?>
					<tr class="<?php echo alternator('odd','even')?>">
						<td><?php echo $id + 1; ?></td>
						<td><?php echo $query['query']?></td>
						<td><?php echo sprintf('%.3f', $query['time'] * 1000)?> ms</td>
						<td><?php echo $query['rows'] == -1 ? '-' : $query['rows'] ?></td>
					</tr>
					<?php 
					$total_time += $query['time'];
					$total_rows += $query['rows'] == -1 ? 0 : $query['rows'];
					?>
				<?php endforeach; ?>
				<tr align="left">
					<th>&nbsp;</th>
					<th><?php echo count($queries) ?> total</th>
					<th><?php echo sprintf('%.3f', $total_time * 1000) ?> ms</th>
					<th><?php echo $total_rows ?></th>
				</tr>
			</table>
		</div>
	<?php endif ?>
	
	<!-- vars and config -->
	<?php if ($panels['vars_and_config']): ?>
		<div id="debug-vars" class="top" style="display: none;">
			<h1>vars &amp; config</h1>
			<ul class="varmenu">
				<li onclick="debugToolbar.showvar(this, 'vars-post'); return false;">POST</li>
				<li onclick="debugToolbar.showvar(this, 'vars-get'); return false;">GET</li>
				<li onclick="debugToolbar.showvar(this, 'vars-server'); return false;">SERVER</li>
				<li onclick="debugToolbar.showvar(this, 'vars-cookie'); return false;">COOKIE</li>
				<li onclick="debugToolbar.showvar(this, 'vars-session'); return false;">SESSION</li>
				<li onclick="debugToolbar.showvar(this, 'vars-config'); return false;">CONFIG</li>
			</ul>
			<div style="display: none;" id="vars-post">
				<?php echo isset($_POST) ? quark_dump($_POST) : quark_dump(array()) ?>
			</div>
			<div style="display: none;" id="vars-get">
				<?php echo isset($_GET) ? quark_dump($_GET) : quark_dump(array()) ?>
			</div>
			<div style="display: none;" id="vars-server">
				<?php echo isset($_SERVER) ? quark_dump($_SERVER) : quark_dump(array()) ?>
			</div>
			<div style="display: none;" id="vars-cookie">
				<?php echo isset($_COOKIE) ? quark_dump($_COOKIE) : quark_dump(array()) ?>
			</div>
			<div style="display: none;" id="vars-session">
				<?php echo isset($_SESSION) ? quark_dump($_SESSION) : quark_dump(array()) ?>
			</div>
			<div style="display: none;" id="vars-config">
				<ul class="configmenu">
					<?php foreach ($configs as $section => $vars): ?>
						<li class="<?php echo alternator('odd', 'even') ?>" onclick="debugToolbar.toggle('vars-config-<?php echo $section ?>'); return false;">
							<div><?php echo $section ?></div>
							<div style="display: none;" id="vars-config-<?php echo $section ?>">
								<?php echo quark_dump($vars) ?>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	<?php endif ?>
	
	<!-- logs and messages -->
	<?php if ($panels['logs']): ?>
		<div id="debug-log" class="top" style="display: none;">
			<h1>logs &amp; msgs</h1>
			<table cellspacing="0" cellpadding="0">
				<tr align="left">
					<th width="1%">#</th>
					<th width="200">time</th>
					<th width="100">level</th>
					<th>message</th>
				</tr>
				<?php foreach ((array)$logs as $id => $log): ?>
					<tr class="<?php echo alternator('odd','even')?>">
						<td><?php echo $id + 1 ?></td>
						<td><?php echo $log[0] ?></td>
						<td><?php echo $log[1] ?></td>
						<td><?php echo $log[2] ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	<?php endif ?>

        <!-- Included Files -->
        <?php if ($panels['files']): ?>
                <div id="debug-files" class="top" style="display: none;">
                        <h1>Files</h1>
                        <table cellspacing="0" cellpadding="0">
                                <tr align="left">
                                        <th>#</th>
                                        <th>file</th>
                                        <th>size</th>
                                        <th>lines</th>
                                </tr>
                                <?php $total_size = $total_lines = 0 ?>
                                <?php foreach ((array)$files as $id => $file): ?>
                                        <?php
                                        $size = filesize($file);
                                        $lines = count(file($file));
                                        ?>
                                        <tr class="<?php echo alternator('odd','even')?>">
                                                <td><?php echo $id + 1 ?></td>
                                                <td><?php echo $file ?></td>
                                                <td><?php echo $size ?></td>
                                                <td><?php echo $lines ?></td>
                                        </tr>
                                        <?php
                                        $total_size += $size;
                                        $total_lines += $lines;
                                        ?>
                                <?php endforeach; ?>
                                <tr align="left">
                                        <th colspan="2">total</th>
                                        <th><?php echo number_format($total_size) ?></th>
                                        <th><?php echo number_format($total_lines) ?></th>
                                </tr>
                        </table>
                </div>
        <?php endif ?>
	
	<!-- ajax -->
	<?php if ($panels['ajax']): ?>
		<div id="debug-ajax" class="top" style="display:none;">
			<h1>ajax</h1>
			<table cellspacing="0" cellpadding="0">
				<tr align="left">
					<th width="1%">#</th>
					<th width="150">source</th>
					<th width="150">status</th>
					<th>request</th>
				</tr>
			</table>
		</div>
	<?php endif ?>

</div>
