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

        <div class="d-flex gap-2">

            <a
                href="/TECHATHON/public/admin"
                class="btn btn-outline-light btn-sm"
            >
                Dashboard
            </a>

            <a
                href="/TECHATHON/public/admin/hackathons"
                class="btn btn-outline-light btn-sm"
            >
                All Hackathons
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

    </div>
</nav>

<main class="container py-5">

    <div class="mb-4">

        <h1>
            Pending Hackathon Approvals
        </h1>

        <p class="text-muted">
            Review hackathons submitted by organizers.
        </p>

    </div>

    <?php if (empty($hackathons)): ?>

        <div class="alert alert-success">
            There are no hackathons waiting for approval.
        </div>

    <?php else: ?>

        <div class="card shadow-sm">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-dark">

                            <tr>
                                <th>ID</th>
                                <th>Hackathon</th>
                                <th>Organizer</th>
                                <th>Category</th>
                                <th>Participation</th>
                                <th>Capacity</th>
                                <th>Registration</th>
                                <th>Hackathon Dates</th>
                                <th>Action</th>
                            </tr>

                        </thead>

                        <tbody>

                        <?php foreach ($hackathons as $hackathon): ?>

                            <tr>

                                <td>
                                    <?= (int) $hackathon['id'] ?>
                                </td>

                                <td>
                                    <strong>
                                        <?= htmlspecialchars(
                                            $hackathon['title']
                                        ) ?>
                                    </strong>

                                    <div class="small text-muted">
                                        <?= htmlspecialchars(
                                            $hackathon['slug']
                                        ) ?>
                                    </div>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $hackathon['organizer_name']
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $hackathon['category_name']
                                            ?? 'Uncategorized'
                                    ) ?>
                                </td>

                                <td>

                                    <?php
                                        $participation =
                                            $hackathon[
                                                'participation_type'
                                            ];
                                    ?>

                                    <?php if (
                                        $participation === 'individual'
                                    ): ?>

                                        <span class="badge text-bg-primary">
                                            Individual
                                        </span>

                                    <?php elseif (
                                        $participation === 'team'
                                    ): ?>

                                        <span class="badge text-bg-success">
                                            Team
                                        </span>

                                    <?php else: ?>

                                        <span class="badge text-bg-info">
                                            Individual + Team
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?php if (
                                        $participation === 'individual'
                                    ): ?>

                                        <?= $hackathon[
                                            'max_participants'
                                        ] !== null
                                            ? (int) $hackathon[
                                                'max_participants'
                                            ]
                                            : 'Unlimited'
                                        ?>
                                        participants

                                    <?php else: ?>

                                        <?= $hackathon['max_teams'] !== null
                                            ? (int) $hackathon['max_teams']
                                            : 'Unlimited'
                                        ?>
                                        teams

                                    <?php endif; ?>

                                </td>

                                <td>
                                    <div class="small">
                                        <strong>Start:</strong><br>
                                        <?= htmlspecialchars(
                                            $hackathon['registration_start']
                                        ) ?>
                                    </div>

                                    <div class="small mt-1">
                                        <strong>End:</strong><br>
                                        <?= htmlspecialchars(
                                            $hackathon['registration_end']
                                        ) ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="small">
                                        <strong>Start:</strong><br>
                                        <?= htmlspecialchars(
                                            $hackathon['hackathon_start']
                                        ) ?>
                                    </div>

                                    <div class="small mt-1">
                                        <strong>End:</strong><br>
                                        <?= htmlspecialchars(
                                            $hackathon['hackathon_end']
                                        ) ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="d-flex gap-2">

                                        <button
                                            type="button"
                                            class="btn btn-success btn-sm"
                                            disabled
                                        >
                                            Approve
                                        </button>

                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm"
                                            disabled
                                        >
                                            Reject
                                        </button>

                                    </div>

                                    <div class="small text-muted mt-2">
                                        Approval actions coming next.
                                    </div>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    <?php endif; ?>

</main>

</body>
</html>