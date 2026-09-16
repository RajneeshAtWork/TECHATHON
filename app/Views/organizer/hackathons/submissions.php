<?php

$hackathonTitle = $hackathon['title'] ?? 'Hackathon';

$submissionCount = count($submissions);

$submittedCount = 0;
$lateCount = 0;
$draftCount = 0;
$withdrawnCount = 0;

foreach ($submissions as $submission) {
    switch ($submission['status'] ?? null) {
        case 'submitted':
            $submittedCount++;
            break;

        case 'late':
            $lateCount++;
            break;

        case 'draft':
            $draftCount++;
            break;

        case 'withdrawn':
            $withdrawnCount++;
            break;
    }
}

function submissionStatusClass(?string $status): string
{
    return match ($status) {
        'submitted' => 'success',
        'late' => 'warning',
        'draft' => 'secondary',
        'withdrawn' => 'danger',
        default => 'light',
    };
}

function submissionStatusLabel(?string $status): string
{
    return match ($status) {
        'submitted' => 'Submitted',
        'late' => 'Late',
        'draft' => 'Draft',
        'withdrawn' => 'Withdrawn',
        default => 'Unknown',
    };
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars($title ?? 'Submissions') ?>
        - TECHATHON
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body class="bg-light">

<div class="container py-4">

    <div
        class="d-flex justify-content-between align-items-center mb-4"
    >

        <div>

            <h1 class="h3 mb-1">
                Submissions
            </h1>

            <p class="text-muted mb-0">
                <?= htmlspecialchars($hackathonTitle) ?>
            </p>

        </div>

        <a
            href="/TECHATHON/public/organizer/hackathons/<?= (int) $hackathon['id'] ?>/manage"
            class="btn btn-outline-secondary"
        >
            Back to Management
        </a>

    </div>


    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Total
                    </div>

                    <div class="fs-4 fw-bold">
                        <?= $submissionCount ?>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Submitted
                    </div>

                    <div class="fs-4 fw-bold">
                        <?= $submittedCount ?>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Late
                    </div>

                    <div class="fs-4 fw-bold">
                        <?= $lateCount ?>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Draft
                    </div>

                    <div class="fs-4 fw-bold">
                        <?= $draftCount ?>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <?php if (empty($submissions)): ?>

                <div class="text-center py-5 px-3">

                    <h5 class="mb-2">
                        No submissions yet
                    </h5>

                    <p class="text-muted mb-0">
                        There are currently no submissions for
                        this hackathon.
                    </p>

                </div>

            <?php else: ?>

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>
                            <th>Project</th>
                            <th>Participant / Team</th>
                            <th>Type</th>
                            <th>Version</th>
                            <th>Status</th>
                            <th>Submitted At</th>
                            <th class="text-end">Action</th>
                        </tr>

                        </thead>

                        <tbody>

                        <?php foreach ($submissions as $submission): ?>

                            <?php
                            $status =
                                $submission['status'] ?? null;

                            $badgeClass =
                                submissionStatusClass($status);
                            ?>

                            <tr>

                                <td>

                                    <div class="fw-semibold">
                                        <?= htmlspecialchars(
                                            $submission['project_title']
                                        ) ?>
                                    </div>

                                    <?php if (
                                        !empty(
                                            $submission['github_url']
                                        )
                                    ): ?>

                                        <div class="small">

                                            <a
                                                href="<?= htmlspecialchars(
                                                    $submission['github_url']
                                                ) ?>"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                            >
                                                GitHub
                                            </a>

                                        </div>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php if (
                                        ($submission[
                                            'registration_type'
                                        ] ?? null) === 'team'
                                    ): ?>

                                        <div class="fw-semibold">

                                            <?= htmlspecialchars(
                                                $submission['team_name']
                                                ?? 'Team'
                                            ) ?>

                                        </div>

                                        <?php if (
                                            !empty(
                                                $submission[
                                                    'team_leader_name'
                                                ]
                                            )
                                        ): ?>

                                            <div class="small text-muted">

                                                Leader:
                                                <?= htmlspecialchars(
                                                    $submission[
                                                        'team_leader_name'
                                                    ]
                                                ) ?>

                                            </div>

                                        <?php endif; ?>

                                    <?php else: ?>

                                        <div class="fw-semibold">

                                            <?= htmlspecialchars(
                                                $submission[
                                                    'participant_name'
                                                ] ?? 'Participant'
                                            ) ?>

                                        </div>

                                        <div class="small text-muted">

                                            <?= htmlspecialchars(
                                                $submission[
                                                    'participant_email'
                                                ] ?? ''
                                            ) ?>

                                        </div>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php if (
                                        ($submission[
                                            'registration_type'
                                        ] ?? null) === 'team'
                                    ): ?>

                                        <span class="badge bg-primary">
                                            Team
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-secondary">
                                            Individual
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>
                                    Version
                                    <?= (int) $submission['version'] ?>
                                </td>


                                <td>

                                    <span
                                        class="badge bg-<?= $badgeClass ?><?= $badgeClass === 'warning'
                                            ? ' text-dark'
                                            : ''
                                        ?>"
                                    >
                                        <?= htmlspecialchars(
                                            submissionStatusLabel(
                                                $status
                                            )
                                        ) ?>
                                    </span>

                                </td>


                                <td>

                                    <?php if (
                                        !empty(
                                            $submission['submitted_at']
                                        )
                                    ): ?>

                                        <?= htmlspecialchars(
                                            $submission['submitted_at']
                                        ) ?>

                                    <?php else: ?>

                                        <span class="text-muted">
                                            —
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td class="text-end">

                                    <a
                                        href="/TECHATHON/public/organizer/hackathons/<?= (int) $hackathon['id'] ?>/submissions/<?= (int) $submission['id'] ?>"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View Submission
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

</body>

</html>