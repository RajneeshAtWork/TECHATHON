<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars($title) ?> - TECHATHON
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container">

        <a
            href="/TECHATHON/public/admin"
            class="navbar-brand"
        >
            TECHATHON Admin
        </a>

        <form
            method="POST"
            action="/TECHATHON/public/logout"
        >
            <button
                type="submit"
                class="btn btn-outline-light btn-sm"
            >
                Logout
            </button>
        </form>

    </div>
</nav>

<main class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1>User Management</h1>

            <p class="text-muted mb-0">
                View and manage TECHATHON users.
            </p>
        </div>

        <a
            href="/TECHATHON/public/admin"
            class="btn btn-secondary"
        >
            Back to Dashboard
        </a>

    </div>

    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (empty($users)): ?>

                        <tr>
                            <td
                                colspan="6"
                                class="text-center text-muted py-4"
                            >
                                No users found.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($users as $user): ?>

                            <tr>

                                <td>
                                    <?= (int) $user['id'] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $user['name']
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $user['email']
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $user['roles'] ?? 'No role'
                                    ) ?>
                                </td>

                                <td>

                                    <?php if (
                                        $user['status'] === 'active'
                                    ): ?>

                                        <span class="badge text-bg-success">
                                            Active
                                        </span>

                                    <?php elseif (
                                        $user['status'] === 'inactive'
                                    ): ?>

                                        <span class="badge text-bg-secondary">
                                            Inactive
                                        </span>

                                    <?php else: ?>

                                        <span class="badge text-bg-danger">
                                            Suspended
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $user['created_at']
                                    ) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</main>

</body>
</html>