<?php

$status = $submission['status'] ?? 'draft';

$statusClass = match ($status) {
    'submitted' => 'success',
    'late' => 'warning',
    'draft' => 'secondary',
    'withdrawn' => 'danger',
    default => 'light',
};

$statusLabel = match ($status) {
    'submitted' => 'Submitted',
    'late' => 'Late',
    'draft' => 'Draft',
    'withdrawn' => 'Withdrawn',
    default => ucfirst($status),
};

$registrationType =
    $submission['registration_type'] ?? 'individual';
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
        <?= htmlspecialchars($title ?? 'Submission') ?>
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
                Submission Details
            </h1>

            <p class="text-muted mb-0">
                <?= htmlspecialchars(
                    $submission['project_title'] ?? 'Project'
                ) ?>
            </p>

        </div>

        <a
            href="/TECHATHON/public/organizer/hackathons/<?= (int) $hackathon['id'] ?>/submissions"
            class="btn btn-outline-secondary"
        >
            Back to Submissions
        </a>

    </div>


    <div class="row g-4">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body">

                    <div
                        class="d-flex justify-content-between align-items-start mb-4"
                    >

                        <div>

                            <div class="text-muted small">
                                Project
                            </div>

                            <h2 class="h4 mb-0">
                                <?= htmlspecialchars(
                                    $submission['project_title']
                                ) ?>
                            </h2>

                        </div>

                        <span
                            class="badge bg-<?= $statusClass ?><?= $statusClass === 'warning'
                                ? ' text-dark'
                                : ''
                            ?>"
                        >
                            <?= htmlspecialchars($statusLabel) ?>
                        </span>

                    </div>


                    <div class="row g-3 mb-4">

                        <div class="col-md-4">

                            <div class="text-muted small">
                                Version
                            </div>

                            <div class="fw-semibold">
                                <?= (int) $submission['version'] ?>
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Submitted At
                            </div>

                            <div class="fw-semibold">
                                <?= !empty(
                                    $submission['submitted_at']
                                )
                                    ? htmlspecialchars(
                                        $submission['submitted_at']
                                    )
                                    : 'Not submitted'
                                ?>
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Participation
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    ucfirst($registrationType)
                                ) ?>
                            </div>

                        </div>

                    </div>


                    <div class="mb-4">

                        <h5 class="mb-2">
                            Project Description
                        </h5>

                        <div style="white-space: pre-line;">

                            <?= nl2br(
                                htmlspecialchars(
                                    $submission[
                                        'project_description'
                                    ] ?? ''
                                )
                            ) ?>

                        </div>

                    </div>


                    <div class="mb-4">

                        <h5 class="mb-2">
                            Submission Notes
                        </h5>

                        <?php if (
                            !empty(
                                $submission['submission_notes']
                            )
                        ): ?>

                            <div style="white-space: pre-line;">

                                <?= nl2br(
                                    htmlspecialchars(
                                        $submission[
                                            'submission_notes'
                                        ]
                                    )
                                ) ?>

                            </div>

                        <?php else: ?>

                            <div class="text-muted">
                                No submission notes provided.
                            </div>

                        <?php endif; ?>

                    </div>


                    <div>

                        <h5 class="mb-3">
                            Project Links
                        </h5>

                        <div class="d-flex flex-wrap gap-2">

                            <?php if (
                                !empty($submission['github_url'])
                            ): ?>

                                <a
                                    href="<?= htmlspecialchars(
                                        $submission['github_url']
                                    ) ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-outline-dark"
                                >
                                    GitHub Repository
                                </a>

                            <?php endif; ?>


                            <?php if (
                                !empty($submission['demo_url'])
                            ): ?>

                                <a
                                    href="<?= htmlspecialchars(
                                        $submission['demo_url']
                                    ) ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-outline-primary"
                                >
                                    Live Demo
                                </a>

                            <?php endif; ?>


                            <?php if (
                                !empty($submission['video_url'])
                            ): ?>

                                <a
                                    href="<?= htmlspecialchars(
                                        $submission['video_url']
                                    ) ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-outline-danger"
                                >
                                    Demo Video
                                </a>

                            <?php endif; ?>


                            <?php if (
                                empty($submission['github_url'])
                                && empty($submission['demo_url'])
                                && empty($submission['video_url'])
                            ): ?>

                                <span class="text-muted">
                                    No external project links.
                                </span>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>


            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h5 class="mb-3">
                        All Project Versions
                    </h5>

                    <?php if (empty($projectSubmissions)): ?>

                        <div class="text-muted">
                            No submission history found.
                        </div>

                    <?php else: ?>

                        <div class="table-responsive">

                            <table class="table align-middle mb-0">

                                <thead class="table-light">

                                <tr>
                                    <th>Version</th>
                                    <th>Status</th>
                                    <th>Submitted At</th>
                                    <th>Action</th>
                                </tr>

                                </thead>

                                <tbody>

                                <?php foreach (
                                    $projectSubmissions
                                    as $version
                                ): ?>

                                    <?php
                                    $versionStatus =
                                        $version['status']
                                        ?? 'draft';

                                    $versionClass =
                                        match ($versionStatus) {
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
                                            <?= (int) $version['version'] ?>

                                        </td>


                                        <td>

                                            <span
                                                class="badge bg-<?= $versionClass ?><?= $versionClass === 'warning'
                                                    ? ' text-dark'
                                                    : ''
                                                ?>"
                                            >
                                                <?= htmlspecialchars(
                                                    ucfirst(
                                                        $versionStatus
                                                    )
                                                ) ?>
                                            </span>

                                        </td>


                                        <td>

                                            <?= !empty(
                                                $version['submitted_at']
                                            )
                                                ? htmlspecialchars(
                                                    $version[
                                                        'submitted_at'
                                                    ]
                                                )
                                                : '—'
                                            ?>

                                        </td>


                                        <td>

                                            <?php if (
                                                (int) $version['id']
                                                === (int) $submission['id']
                                            ): ?>

                                                <span
                                                    class="text-muted small"
                                                >
                                                    Current
                                                </span>

                                            <?php else: ?>

                                                <span
                                                    class="text-muted small"
                                                >
                                                    Version
                                                    <?= (int) $version['version'] ?>
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

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body">

                    <h5 class="mb-3">
                        Participant
                    </h5>

                    <?php if ($registrationType === 'team'): ?>

                        <div class="mb-3">

                            <div class="text-muted small">
                                Team
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    $submission['team_name']
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
                                    $submission[
                                        'team_leader_name'
                                    ] ?? '—'
                                ) ?>
                            </div>

                            <?php if (
                                !empty(
                                    $submission[
                                        'team_leader_email'
                                    ]
                                )
                            ): ?>

                                <div class="small text-muted">

                                    <?= htmlspecialchars(
                                        $submission[
                                            'team_leader_email'
                                        ]
                                    ) ?>

                                </div>

                            <?php endif; ?>

                        </div>

                    <?php else: ?>

                        <div class="mb-3">

                            <div class="text-muted small">
                                Participant
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    $submission[
                                        'participant_name'
                                    ] ?? '—'
                                ) ?>
                            </div>

                        </div>


                        <div>

                            <div class="text-muted small">
                                Email
                            </div>

                            <div>
                                <?= htmlspecialchars(
                                    $submission[
                                        'participant_email'
                                    ] ?? '—'
                                ) ?>
                            </div>

                        </div>

                    <?php endif; ?>

                </div>

            </div>


            <?php if ($registrationType === 'team'): ?>

                <div class="card border-0 shadow-sm">

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

                                    <?php
                                    $memberRole =
                                        $member['role']
                                        ?? 'member';

                                    $roleClass =
                                        $memberRole === 'leader'
                                            ? 'bg-warning text-dark'
                                            : 'bg-light text-dark';
                                    ?>

                                    <div class="list-group-item px-0">

                                        <div
                                            class="d-flex justify-content-between align-items-start"
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

</body>

</html>