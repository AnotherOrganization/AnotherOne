<?php
	defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<main class="container">
	<div class="row">
		<div class="col-md-6">
			<h4>Welcome Back, <?php echo $name ?>.</h4>
			<div>
				<a class="front-menu" href="<?php echo site_url('students/profile') ?>"><i class="glyphicon glyphicon-user"></i> Student Profile</a>
				<div class="dropdown front-menu">
					<div class="dropdown-toggle" type="button" id="enroll-semester" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						<i class="glyphicon glyphicon-pencil"></i> Register Now
					</div>
					<ul class="dropdown-menu front-dropdown" aria-labelledby="enroll-semester">
						<?php foreach($semesters as $semester):
							$name =  url_title($semester->name); ?>
							<li><a href="<?= site_url('students/enroll/'.strtolower($name)) ?>"><?= $semester->name ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<a class="front-menu" href="<?php echo site_url('courses/sections') ?>"><i class="glyphicon glyphicon-search"></i> View Available Courses</a>
				<a class="front-menu" href="<?php echo site_url('students/view') ?>"><i class="glyphicon glyphicon-calendar"></i> View Schedule</a>
			</div>
		</div>
		<div class="col-md-6" style="margin-top: 39px">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-bullhorn fa-lg"></i> 
					Breaking News
				</div>
				<div class="panel-body">
					<div id="notice-slider" class="carousel slide sidebar-slider" data-interval="10000" data-ride="carousel">
						<!-- Carousel indicators -->
						<!-- Carousel items -->
						<div class="carousel-inner">
							<?php for($i = 0; $i < 10; $i++): ?>
							<div class="<?php if($i == 0) echo 'active' ;?> item">
								<h4><a target="_blank" href="<?php echo $news[$i]['url'] ?>" ><?php echo $news[$i]['title'] ?></a></h4>
								<p><?php echo $news[$i]['abstract'] ?></p>
							</div>
							<?php endfor; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 text-center" style="margin-top: 20px">
			<h3>Progress</h3>
		</div>
		<?php
			$num_course = count($progress);
			$num_per_col = ceil($num_course / 3);

			$current = 0;
			for($col = 0; $col < $num_per_col; $col++)
			{
				echo '<div class="col-md-4">
						<ul class="list-group">';
					for($i = 0; $i < $num_per_col; $i++)
					{
						if($current >= $num_course)
							continue;

						echo '<li class="list-group-item';
						if($progress[$current]['completed'])
							echo ' list-group-item-success';
						else if($progress[$current]['takable'])
							echo ' list-group-item-warning';
						echo '">' . $progress[$current]['name'] . '</li>';

						$current++;
					}
				echo '</ul>
					</div>';
			}
		?>
	</div>
</main>
