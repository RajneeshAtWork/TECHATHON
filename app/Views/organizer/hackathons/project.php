<?php

$projectTitle = $project['title'] ?? 'Project';

$registrationType =
    $project['registration_type'] ?? 'individual';

$latestSubmission =
    $submissions[0] ?? null;
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
        <?= htmlspecialchars($title ?? 'Project') ?>
        - TECHATHON
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h1 class="h3 mb-1">
                <?= htmlspecialchars($projectTitle) ?>
            </h1>

            <p class="text-muted mb-0">
                <?= htmlspecialchars(
                    $project['hackathon_title'] ?? ''
                ) ?>
            </p>

        </div>

        <a
            href="/TECHATHON/public/organizer/hackathons/<?= (int) $hackathon['id'] ?>/projects"
            class="btn btn-outline-secondary"
        >
            Back to Projects
        </a>

    </div>


    <div class="row g-4">

        <div class="col-lg-8">

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-body">

                    <h5 class="mb-3">
                        Project Information
                    </h5>

                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Description
                        </div>

                        <div style="white-space: pre-line;">
                            <?= nl2br(
                                htmlspecialchars(
                                    $project['description'] ?? ''
                                )
                            ) ?>
                        </div>

                    </div>


                    <div class="row g-3">

                        <?php if (!empty($project['github_url'])): ?>

                            <div class="col-md-4">

                                <a
                                    href="<?= htmlspecialchars(
                                        $project['github_url']
                                    ) ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-outline-dark w-100"
                                >
                                    GitHub Repository
                                </a>

                            </div>

                        <?php endif; ?>


                        <?php if (!empty($project['demo_url'])): ?>

                            <div class="col-md-4">

                                <a
                                    href="<?= htmlspecialchars(
                                        $project['demo_url']
                                    ) ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-outline-primary w-100"
                                >
                                    Live Demo
                                </a>

                            </div>

                        <?php endif; ?>


                        <?php if (!empty($project['video_url'])): ?>

                            <div class="col-md-4">

                                <a
                                    href="<?= htmlspecialchars(
                                        $project['video_url']
                                    ) ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-outline-danger w-100"
                                >
                                    Demo Video
                                </a>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            </div>


            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h5 class="mb-3">
                        Submission History
                    </h5>

                    <?php if (empty($submissions)): ?>

                        <div class="alert alert-light mb-0">
                            No submissions have been created for
                            this project yet.
                        </div>

                    <?php else: ?>

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead class="table-light">

                                <tr>
                                    <th>Version</th>
                                    <th>Status</th>
                                    <th>Submitted At</th>
                                    <th>Notes</th>
                                </tr>

                                </thead>

                                <tbody>

                                <?php foreach (
                                    $submissions as $submission
                                ): ?>

                                    <?php
                                    $status =
                                        $submission['status']
                                        ?? 'draft';

                                    $statusClass = match ($status) {
                                        'submitted' => 'success',
                                        'late' => 'warning',
                                        'draft' => 'secondary',
                                        'withdrawn' => 'danger',
                                        default => 'light',
                                    };
                                    ?>

                                    <tr>

                                        <td class="fw-semibold">
                                            Version
                                            <?= (int) $submission['version'] ?>
                                        </td>

                                        <td>

                                            <span
                                                class="badge bg-<?= $statusClass ?><?=
                                                    $statusClass === 'warning'
                                                        ? ' text-dark'
                                                        : ''
                                                ?>"
                                            >
                                                <?= htmlspecialchars(
                                                    ucfirst($status)
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

                                        <td>

                                            <?php if (
                                                !empty(
                                                    $submission[
                                                        'submission_notes'
                                                    ]
                                                )
                                            ): ?>

                                                <?= nl2br(
                                                    htmlspecialchars(
                                                        $submission[
                                                            'submission_notes'
                                                        ]
                                                    )
                                                ) ?>

                                            <?php else: ?>

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            <?php endif; ?>

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


        <div class="col-lg-4">

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-body">

                    <h5 class="mb-3">
                        Registration
                    </h5>

                    <div class="mb-3">

                        <div class="text-muted small">
                            Participation Type
                        </div>

                        <div class="fw-semibold">
                            <?= htmlspecialchars(
                                ucfirst($registrationType)
                            ) ?>
                        </div>

                    </div>


                    <?php if ($registrationType === 'team'): ?>

                        <div class="mb-3">

                            <div class="text-muted small">
                                Team
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    $project['team_name']
                                    ?? 'Team'
                                ) ?>
                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="text-muted small">
                                Team Leader
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    $project[
                                        'team_leader_name'
                                    ] ?? '—'
                                ) ?>
                            </div>

                        </div>

                    <?php else: ?>

                        <div class="mb-3">

                            <div class="text-muted small">
                                Participant
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    $project[
                                        'participant_name'
                                    ] ?? '—'
                                ) ?>
                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="text-muted small">
                                Email
                            </div>

                            <div>
                                <?= htmlspecialchars(
                                    $project[
                                        'participant_email'
                                    ] ?? '—'
                                ) ?>
                            </div>

                        </div>

                    <?php endif; ?>

                </div>

            </div>


            <?php if ($registrationType === 'team'): ?>

                <div class="card shadow-sm border-0">

                    <div class="card-body">

                        <h5 class="mb-3">
                            Team Members
                        </h5>

                        <?php if (empty($teamMembers)): ?>

                            <div class="text-muted">
                                No team members found.
                            </div>

                        <?php else: ?>

                            <div class="list-group list-group-flush">

                                <?php foreach (
                                    $teamMembers as $member
                                ): ?>

                                    <div
                                        class="list-group-item px-0"
                                    >

                                        <div
                                            class="d-flex justify-content-between"
                                        >

                                            <div>

                                                <div class="fw-semibold">
                                                    <?= htmlspecialchars(
                                                        $member['name']
                                                    ) ?>
                                                </div>

                                                <div class="small text-muted">
                                                    <?= htmlspecialchars(
                                                        $member['email']
                                                    ) ?>
                                                </div>

                                            </div>


                                            <?php
                                            $memberRole =
                                                $member['role']
                                                ?? 'member';

                                            $roleClass =
                                                $memberRole === 'leader'
                                                    ? 'bg-warning text-dark'
                                                    : 'bg-light text-dark';
                                            ?>

                                            <span
                                                class="badge <?= $roleClass ?>"
                                            >
                                                <?= htmlspecialchars(
                                                    ucfirst(
                                                        $memberRole
                                                    )
                                                ) ?>
                                            </span>

                                        </div>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>
</html>