<div class="page-header">
	<h1>List of Users</h1>                
	<a href="<?= site_url('user/users/add') ?>" class="btn btn-primary">Create User</a>
</div>
<table class="table">
    <thead>
        <th>Name</th>
        <th>Email</th>
        <th>Last Login</th>
    </thead>
    <tbody>
        <? foreach($main AS $user) { ?>
            <tr>
                <td><a href="<?= site_url('user/users/edit/' . $user->id) ?>"><?= $user->get_fullname() ?></a></td>
                <td><?= $user->email ?></td>
                <td><?= $user->get_last_login() ?></td>
            </tr>
        <? } ?>
    </tbody>
</table>