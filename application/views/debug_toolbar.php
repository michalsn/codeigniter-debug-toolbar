<?php defined('BASEPATH') or die('No direct script access.') ?>

<?php
	//$this->load->helper(array('date', 'url', 'html', 'string', 'debug'));
?>

<style type="text/css">
<?php echo $styles ?>
</style>

<script type="text/javascript">
<?php echo $scripts ?>
</script>

<div id="codeigniter-debug-toolbar">

	<!-- toolbar -->
	<div id="debug-toolbar">
		<!-- Kohana link -->
<!--		<?php echo anchor(
			"http://codeigniter.com",
			img($this->config->item('icon_path').'/debug_toolbar_codeigniter.png'),
			array('target' => '_blank')
		) ?>
-->
		<ul class="menu">

			<!-- Codeigniter version -->
			<li title="Codeigniter Version" class="first">
				<?php echo anchor("http://codeigniter.com/", img($this->config->item('icon_path').'/debug_toolbar_codeigniter.png'),array('target' => '_blank')) ?>
				<?php echo anchor("http://codeigniter.com/", CI_VERSION, array('target' => '_blank')) ?>
			</li>

			<!-- Benchmarks -->
			<?php if ($panels['benchmarks']):
				foreach ((array)$benchmarks as $benchmark):
					$ElapsedTime = isset($ElapsedTime) ? $ElapsedTime + $benchmark['diff'] : $benchmark['diff'];
					endforeach;
				?>
				<li id="time" onclick="debugToolbar.show('debug-benchmarks'); return false;" title="Time">
					<?php echo img($this->config->item('icon_path').'/debug_toolbar_time.png') ?>
					<?php echo sprintf('%.2f', $ElapsedTime * 1000) ?> ms
				</li>
				<li id="memory" onclick="debugToolbar.show('debug-benchmarks'); return false;" title="Memory">
					<?php echo img($this->config->item('icon_path').'/debug_toolbar_memory.png') ?>
					{memory_usage}
				</li>
			<?php endif ?>

			<!-- Queries -->
			<?php if ($panels['database']): ?>
				<li id="toggle-database" onclick="debugToolbar.show('debug-database'); return false;" title="SQL Log">
					<?php echo img($this->config->item('icon_path').'/debug_toolbar_database.png') ?>
					SQL Log (<?php echo isset($queries) ? count($queries) : 0 ?>)
				</li>
			<?php endif ?>

			<!-- Vars and Config -->
			<?php if ($panels['requests']): ?>
				<li id="toggle-vars" onclick="debugToolbar.show('debug-vars'); return false;" title="Request">
					<?php echo img($this->config->item('icon_path').'/debug_toolbar_requests.png') ?>
					Requests
				</li>
			<?php endif ?>
			<?php if ($panels['configs']): ?>
				<li id="toggle-configs" onclick="debugToolbar.show('debug-configs'); return false;" title="Configs">
					<?php echo img($this->config->item('icon_path').'/debug_toolbar_config.png') ?>
					Configs
				</li>
				<?php endif ?>
				<?php if ($panels['sessions']): ?>
				<li id="toggle-configs" onclick="debugToolbar.show('debug-sessions'); return false;" title="Sessions">
					<?php echo img($this->config->item('icon_path').'/debug_toolbar_sessions.png') ?>
					Sessions
				</li>
				<?php endif ?>
				<?php if ($panels['cookies']): ?>
				<li id="toggle-cookies" onclick="debugToolbar.show('debug-cookies'); return false;" title="Cookies">
					<?php echo img($this->config->item('icon_path').'/debug_toolbar_cookies.png') ?>
					Cookies
				</li>
			<?php endif ?>

			<!-- Logs -->
			<?php if ($panels['logs']): ?>
				<li id="toggle-log" onclick="debugToolbar.show('debug-log'); return false;" title="Log">
					<?php echo img($this->config->item('icon_path').'/debug_toolbar_logs.png') ?>
					Logs
				</li>
			<?php endif ?>

			<!-- Ajax -->
			<?php if ($panels['ajax']): ?>
				<li id="toggle-ajax" onclick="debugToolbar.show('debug-ajax'); return false;" style="display: none" title="Ajax">
					<?php echo img($this->config->item('icon_path').'/debug_toolbar_ajax.png') ?>
					Ajax (<span>0</span>)
				</li>
			<?php endif ?>


      <!-- Files -->
      <?php if ($panels['files']): ?>
              <li id="toggle-files" onclick="debugToolbar.show('debug-files'); return false;" title="Files">
                      <?php echo img($this->config->item('icon_path').'/debug_toolbar_files.png') ?>
                      files
              </li>
      <?php endif ?>

			<!-- Swap sides
			<li onclick="debugToolbar.swap(); return false;">
				<?php echo img($this->config->item('icon_path').'/debug_toolbar_text_align_left.png') ?>
			</li>
			-->

			<!-- Close -->
			<li class="last" onclick="debugToolbar.close(); return false;" title="Close">
				<?php echo img($this->config->item('icon_path').'/debug_toolbar_close.png') ?>
			</li>
		</ul>
	</div>

	<!-- benchmarks -->
	<?php if ($panels['benchmarks']): ?>
		<div id="debug-benchmarks" class="top" style="display: none;">
			<h1>Time & Memory</h1>
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
	<?php if ($panels['requests']): ?>
		<div id="debug-vars" class="top" style="display: none;">
			<h1>Requests</h1>
			<ul class="varmenu">
				<li onclick="debugToolbar.showvar(this, 'vars-post'); return false;">POST</li>
				<li onclick="debugToolbar.showvar(this, 'vars-get'); return false;">GET</li>
			</ul>
			<div style="display: none;" id="vars-post">
				<table cellspacing="0" cellpadding="0">
					<tr align="left">
						<th width="50%">Key</th>
						<th width="50%">Value</th>
					</tr>
					<?php foreach ((array)$_POST as $key => $value): ?>
						<tr class="<?php echo alternator('odd','even')?>">
							<td><?php echo $key ?></td>
							<td><?php echo $value ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
			<div style="display: none;" id="vars-get">
				<table cellspacing="0" cellpadding="0">
					<tr align="left">
						<th width="50%">Key</th>
						<th width="50%">Value</th>
					</tr>
					<?php foreach ((array)$_GET as $key => $value): ?>
						<tr class="<?php echo alternator('odd','even')?>">
							<td><?php echo $key ?></td>
							<td><?php echo $value ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
		</div>
	<?php endif ?>

	<!-- cookies -->
	<?php if ($panels['cookies']): ?>
		<div id="debug-cookies" class="top" style="display: none;">
			<h1>Cookies</h1>
			<div id="vars-cookie">
				<table cellspacing="0" cellpadding="0">
					<tr align="left">
						<th width="50%">Key</th>
						<th width="50%">Value</th>
					</tr>
					<?php foreach ((array)$_COOKIE as $key => $value): ?>
						<tr class="<?php echo alternator('odd','even')?>">
							<td><?php echo $key ?></td>
							<td><?php echo $value ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
		</div>
	<?php endif ?>

	<!-- sessions -->
	<?php if ($panels['sessions']): ?>
		<div id="debug-sessions" class="top" style="display: none;">
			<h1>Sessions</h1>
			<div id="vars-session">
				<table cellspacing="0" cellpadding="0">
					<tr align="left">
						<th width="50%">Key</th>
						<th width="50%">Value</th>
					</tr>
					<?php if (isset($_SESSION)): ?>
						<?php foreach ((array)$_SESSION as $key => $value): ?>
							<tr class="<?php echo alternator('odd','even')?>">
								<td><?php echo $key ?></td>
								<td><?php echo $value ?></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</table>
			</div>
		</div>
	<?php endif ?>

	<!-- configs -->
	<?php if ($panels['configs']): ?>
		<div id="debug-configs" class="top" style="display: none;">
			<h1>Configs</h1>
			<div id="vars-config">
				<ul class="configmenu">
					<?php foreach ($configs as $section => $vars): ?>
						<li class="<?php echo alternator('odd', 'even') ?>" onclick="debugToolbar.toggle('vars-config-<?php echo $section ?>'); return false;">
							<div><?php echo $section ?></div>
							<div style="display: none;" id="vars-config-<?php echo $section ?>">
								<table cellspacing="0" cellpadding="0">
									<tr align="left">
										<th width="50%">Key</th>
										<th width="50%">Value</th>
									</tr>
									<?php foreach ((array)$vars as $key => $value): ?>
										<tr class="autoBackground">
											<td><?php echo $key ?></td>
											<td><?php echo $value ?></td>
										</tr>
									<?php endforeach; ?>
								</table>
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
			<h1>Logs</h1>
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
			<h1>Ajax</h1>
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
