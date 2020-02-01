<?php
session_start();
if ( !isset($_SESSION['usr_id']) || !isset($_SESSION['username']) ) {
	header('Location: /login/'); //User is not logged in. Redirect them to login page.
	exit;
}

?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>My Agenda</title>
	
	<!-- FullCalendar -->
	<link href='css/fullcalendar.min.css' rel='stylesheet' />
    <!-- Bootstrap Core CSS -->
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css'>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js'></script>
	<script src='js/moment.min.js'></script>
   
   <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
	<!-- FullCalendar -->
	<script src='js/fullcalendar.min.js'></script>
	 <!-- Bootstrap Core JavaScript -->
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js'></script>

    <!-- Custom CSS -->
	<style>
        #calendar {
            max-width: 1200px;
            margin-bottom: 30px;
        }
        .nocheckbox {
            display: none;
        }
        .label-on {
            border-radius: 3px;
            background: red;
            color: #ffffff;
            padding: 6px 10px;
            border: 1px solid red;
            display: table-cell;
        }
        .label-off {
            border-radius: 3px;
            background: white;
            border: 1px solid red;
            padding: 6px 10px;
            display: table-cell;
        }

        #calendar a.fc-event {
            color: #fff; /* bootstrap default styles make it black. undo */
            background-color: #0065A6;
        }
    </style>

</head>
<body>

	<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<div class="container">
		<a class="navbar-brand" href="/index.php">Patients DB</a>
		<div class="mr-sm-2">
			<a href="/logout.php" class="navbar-item">Logout</a>
		</div>
	</div>
	</nav>
<br>
<!-- Page Content -->
<div class="container">

	<div class="row">
		<div class="col-lg-12 text-center">
		<div style="height:20px"></div>
			<div id="calendar" class="col-centered">
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form class="form-horizontal" method="POST" action="methods/post-events.php">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel">Add Event</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="form-group">
							<label for="start" class="col-sm-12 control-label">Start date</label>
							<div class="col-sm-12">
								<input type="text" name="start" class="form-control" id="start" placeholder="DD/MM/YYYY">
							</div>
						</div>
						<div class="form-group">
							<label for="end" class="col-sm-12 control-label">End date</label>
							<div class="col-sm-12">
								<input type="text" name="end" class="form-control" id="end">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label for="title" class="col-sm-12 control-label">Title</label>
							<div class="col-sm-12">
								<input type="text" name="title" class="form-control" id="title" placeholder="Title">
							</div>
						</div>
						<div class="form-group">
							<label for="color" class="col-sm-12 control-label">Color</label>
							<div class="col-sm-12">
								<select name="color" class="form-control" id="color">
									<option style="color:#0071c5;" value="#0071c5">&#9724; Dark blue</option>
									<option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquoise</option>
									<option style="color:#008000;" value="#008000">&#9724; Green</option>						  
									<option style="color:#FFD700;" value="#FFD700">&#9724; Yellow</option>
									<option style="color:#FF8C00;" value="#FF8C00">&#9724; Orange</option>
									<option style="color:#FF0000;" value="#FF0000">&#9724; Red</option>
									<option style="color:#000;" value="#000">&#9724; Black</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label for="description" class="col-12 control-label">Description</label>
							<div class="col-12">
								<input type="text" name="description" class="form-control" id="description" placeholder="Description">
								<input type="hidden" id="token" name="token" value="<?=$_SESSION['token']?>">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Save</button>
				</div>
				</form>
			</div>
		</div>
	</div>
		
	<!-- Modal -->
	<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form class="form-horizontal" method="POST" action="methods/edit-events.php">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel">Edit Event</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="form-group">
							<label for="start" class="col-sm-12 control-label">Start date</label>
							<div class="col-sm-12">
								<input type="text" name="start" class="form-control" id="start">
							</div>
						</div>
						<div class="form-group">
							<label for="end" class="col-sm-12 control-label">End date</label>
							<div class="col-sm-12">
								<input type="text" name="end" class="form-control" id="end">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label for="title" class="col-sm-12 control-label">Title</label>
							<div class="col-sm-12">
								<input type="text" name="title" class="form-control" id="title" placeholder="Title">
							</div>
						</div>
						<div class="form-group">
							<label for="color" class="col-sm-12 control-label">Color</label>
							<div class="col-sm-12">
								<select name="color" class="form-control" id="color">
									<option style="color:#0071c5;" value="#0071c5">&#9724; Dark blue</option>
									<option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquoise</option>
									<option style="color:#008000;" value="#008000">&#9724; Green</option>						  
									<option style="color:#FFD700;" value="#FFD700">&#9724; Yellow</option>
									<option style="color:#FF8C00;" value="#FF8C00">&#9724; Orange</option>
									<option style="color:#FF0000;" value="#FF0000">&#9724; Red</option>
									<option style="color:#000;" value="#000">&#9724; Black</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label for="description" class="col-12 control-label">Description</label>
							<div class="col-12">
								<input type="text" name="description" class="form-control" id="description" placeholder="Description">
								<input type="hidden" id="token" name="token" value="<?=$_SESSION['token']?>">
							</div>
						</div>
						<div class="form-group" id="del"> 
							<label class="col-sm-12 control-label">Delete Event</label>
							<div class="col-sm-12">
								<label onclick="toggleCheck('check1');" class="label-off" for="check1" id="check1_label">Delete</label>
							</div>
							<input class="nocheckbox" type="checkbox" id="check1" name="delete">
						</div>
					</div>
					<script>
					function toggleCheck(check) {
						if ($('#'+check).is(':checked')) {
							$('#'+check+'_label').removeClass('label-on');
							$('#'+check+'_label').addClass('label-off');
						} else {
							$('#'+check+'_label').addClass('label-on');
							$('#'+check+'_label').removeClass('label-off');
						}
					}		  
					</script>
					<input type="hidden" name="id" class="form-control" id="id">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Save</button>
				</div>
				</form>
			</div>
		</div>
	</div>

</div>

<script>
	$(function() {
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next, today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay, listWeek'
			},
			height: 540,
			businessHours: {
			  daysOfWeek: [ 1, 2, 3, 4, 5 ],
			  start: '15:00',
			  end: '22:00',
			},
			nowIndicator: true,
			defaultDate: new Date(), 
			editable: true,
			editable: true,
			navLinks: true,
			eventLimit: true, // allow "more" link when there are too many events
			selectable: true,
			selectHelper: true,
			select: function(start, end) {
				$('#ModalAdd #start').val(start.format('DD/MM/YYYY HH:mm'));
				$('#ModalAdd #end').val(end.format('DD/MM/YYYY HH:mm'));
				$('#ModalAdd').modal('show');
			},
			eventAfterRender: function(eventObj, $el) {
				var request = new XMLHttpRequest();
				request.open('GET', 'methods/get-events.php', true);
				request.onload = function () {
					$el.popover({
						title: eventObj.title,
						content: eventObj.description,
						trigger: 'hover',
						placement: 'top',
						container: 'body'
					});
				}
			request.send();
			},
			eventRender: function(event, element) {
				element.bind('dblclick', function() {
					$('#ModalEdit #id').val(event.id);
					$('#ModalEdit #start').val(event.start.format('DD/MM/YYYY HH:mm'));
					$('#ModalEdit #end').val(event.end.format('DD/MM/YYYY HH:mm'));
					$('#ModalEdit #title').val(event.title);
					$('#ModalEdit #description').val(event.description);
					$('#ModalEdit #color').val(event.color);
					$('#ModalEdit').modal('show');
				});
			},
			eventDrop: function(event, delta, revertFunc) { 
				edit(event);
			},
			eventResize: function(event,dayDelta,minuteDelta,revertFunc) { 
				edit(event);
			},
            events: {
                url: 'methods/get-events.php',
                error: function() {
                $('#script-warning').show();
                }
            },
            loading: function(bool) {
                $('#loading').toggle(bool);
            }
		});
		function edit(event) {
			start = event.start.format('DD/MM/YYYY HH:mm');
			if (event.end) {
				end = event.end.format('DD/MM/YYYY HH:mm');
			} else {
				end = start;
			}
			id = event.id;
			Event = [];
			Event[0] = id;
			Event[1] = start;
			Event[2] = end;
			
			$.ajax({
			 url: 'methods/edit-events.php',
			 type: "POST",
			 data: {Event:Event},
			 success: function(rep) {
				 alert('Saved');
				}
			});
		}
	});
</script>
</body>
</html>
