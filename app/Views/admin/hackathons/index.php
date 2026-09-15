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

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="mb-1">
                Hackathon Management
            </h1>

            <p class="text-muted mb-0">
                View and manage all hackathons on the platform.
            </p>
        </div>

        <a
            href="/TECHATHON/public/admin/hackathons/pending"
            class="btn btn-warning"
        >
            Pending Approvals
        </a>

    </div>

    <?php if (empty($hackathons)): ?>

        <div class="alert alert-info">
            No hackathons have been created yet.
        </div>

    <?php else: ?>

        <div class="card shadow-sm">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-dark">

                            <tr>
                                <th>ID</th>
                                <th>Hackathon</th>
                                <th>Organizer</th>
                                <th>Category</th>
                                <th>Participation</th>
                                <th>Capacity</th>
                                <th>Status</th>
                                <th>Created</th>
                            </tr>

                        </thead>

                        <tbody>

                        <?php foreach ($hackathons as $hackathon): ?>

                            <?php
                                $status = $hackathon['status'] ?? 'unknown';

                                $statusClass = match ($status) {
                                    'approved' => 'success',
                                    'pending_approval' => 'warning',
                                    'registration_open' => 'primary',
                                    'registration_closed' => 'secondary',
                                    'ongoing' => 'info',
                                    'submission_closed' => 'secondary',
                                    'judging' => 'dark',
                                    'results_published' => 'success',
                                    'completed' => 'dark',
                                    'rejected' => 'danger',
                                    default => 'secondary',
                                };
                            ?>

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

                                    <?php if ($participation === 'individual'): ?>

                                        <span class="badge text-bg-primary">
                                            Individual
                                        </span>

                                    <?php elseif ($participation === 'team'): ?>

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

                                        <span>
                                            <?= $hackathon[
                                                'max_participants'
                                            ] !== null
                                                ? (int) $hackathon[
                                                    'max_participants'
                                                ]
                                                : 'Unlimited'
                                            ?>
                                            participants
                                        </span>

                                    <?php elseif (
                                        $participation === 'team'
                                    ): ?>

                                        <div>
                                            <?= $hackathon['max_teams'] !== null
                                                ? (int) $hackathon['max_teams']
                                                : 'Unlimited'
                                            ?>
                                            teams
                                        </div>

                                        <?php if (
                                            $hackathon['max_team_size']
                                            !== null
                                        ): ?>

                                            <div class="small text-muted">
                                                Max
                                                <?= (int) $hackathon[
                                                    'max_team_size'
                                                ] ?>
                                                members
                                            </div>

                                        <?php endif; ?>

                                    <?php else: ?>

                                        <div>
                                            <?=
                                                $hackathon['max_participants']
                                                !== null
                                                    ? (int) $hackathon[
                                                        'max_participants'
                                                    ]
                                                    : 'Unlimited'
                                            ?>
                                            individual participants
                                        </div>

                                        <div>
                                            <?=
                                                $hackathon['max_teams']
                                                !== null
                                                    ? (int) $hackathon[
                                                        'max_teams'
                                                    ]
                                                    : 'Unlimited'
                                            ?>
                                            teams
                                        </div>

                                    <?php endif; ?>

                                </td>

                                <td>
                                    <span
                                        class="badge text-bg-<?= $statusClass ?>"
                                    >
                                        <?= htmlspecialchars(
                                            ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $status
                                                )
                                            )
                                        ) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="small">
                                        <?= htmlspecialchars(
                                            $hackathon['created_at']
                                        ) ?>
                                    </span>
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