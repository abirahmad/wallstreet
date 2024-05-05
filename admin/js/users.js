$(document).ready(function () {
	var usersData = $('#userList').DataTable({
		"lengthChange": false,
		"processing": true,
		"serverSide": true,
		"order": [],
		"ajax": {
			url: "action.php",
			type: "POST",
			data: { action: 'listUser' },
			dataType: "json"
		},
		"columnDefs": [
			{
				"targets": [0, 7, 8],
				"orderable": false,
			},
		],
		"pageLength": 10
	});
	$('#addUser').click(function () {
		$('#userModal').modal('show');
		$('#userForm')[0].reset();
		$('#passwordSection').show();
		$('.modal-title').html("<i class='fa fa-plus'></i> Add User");
		$('#action').val('addUser');
		$('#save').val('Add User');
	});
	
	$(document).on('submit', '#userForm', function (event) {
		event.preventDefault();
		$('#save').attr('disabled', 'disabled');
		var formData = $(this).serialize();
		console.log('formData', formData)
		$.ajax({
			url: "action.php",
			method: "POST",
			data: formData,
			success: function (data) {
				$('#userForm')[0].reset();
				$('#userModal').modal('hide');
				$('#save').attr('disabled', false);
				usersData.ajax.reload();
			}
		})
	});
});