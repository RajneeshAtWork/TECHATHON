<?php

$hackathonTitle = $hackathon['title'] ?? 'Hackathon';

$projectCount = count($projects);

function projectStatusBadge(?string $status): string
{
    return match ($status) {
        'submitted' => 'success',
        'late' => 'warning',
        'draft' => 'secondary',
        'withdrawn' => 'danger',
        default => 'light',
    };
}

function projectStatusLabel(?string $status): string
{
    if (!$status) {
        return 'No Submission';
    }

    return match ($status) {
        'submitted' => 'Submitted',
        'late' => 'Late',
        'draft' => 'Draft',
        'withdrawn' => 'Withdrawn',
        default => ucfirst($status),
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
        <?= htmlspecialchars($title ?? 'Projects') ?>
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
                Projects
            </h1>

            <p class="text-muted mb-0">
                <?= htmlspecialchars($hackathonTitle) ?>
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="/TECHATHON/public/organizer/hackathons/<?= (int) $hackathon['id'] ?>/manage"
                class="btn btn-outline-secondary"
            >
                Back to Management
            </a>

        </div>

    </div>


    <div class="row mb-4">

        <div class="col-md-4">

            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <div class="text-muted small">
                        Total Projects
                    </div>

                    <div class="fs-3 fw-bold">
                        <?= $projectCount ?>
                    </div>

                </div>
            </div>

        </div>

    </div>


    <div class="card shadow-sm border-0">

        <div class="card-body p-0">

            <?php if (empty($projects)): ?>

                <div class="text-center py-5">

                    <h5 class="mb-2">
                        No projects yet
                    </h5>

                    <p class="text-muted mb-0">
                        Participants have not created any projects
                        for this hackathon yet.
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
                            <th>Latest Version</th>
                            <th>Status</th>
                            <th>Updated</th>
                            <th class="text-end">Action</th>
                        </tr>

                        </thead>

                        <tbody>

                        <?php foreach ($projects as $project): ?>

                            <?php
                            $submissionStatus =
                                $project['latest_submission_status'] ?? null;

                            $badgeClass =
                                projectStatusBadge($submissionStatus);
                            ?>

                            <tr>

                                <td>

                                    <div class="fw-semibold">
                                        <?= htmlspecialchars(
                                            $project['title']
                                        ) ?>
                                    </div>

                                    <?php if (!empty($project['github_url'])): ?>
                                        <div class="small">
                                            <a
                                                href="<?= htmlspecialchars($project['github_url']) ?>"
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
                                        ($project['registration_type'] ?? null)
                                        === 'team'
                                    ): ?>

                                        <div class="fw-semibold">
                                            <?= htmlspecialchars(
                                                $project['team_name']
                                                ?? 'Team'
                                            ) ?>
                                        </div>

                                        <?php if (
                                            !empty(
                                                $project['team_leader_name']
                                            )
                                        ): ?>

                                            <div class="small text-muted">
                                                Leader:
                                                <?= htmlspecialchars(
                                                    $project['team_leader_name']
                                                ) ?>
                                            </div>

                                        <?php endif; ?>

                                    <?php else: ?>

                                        <div class="fw-semibold">
                                            <?= htmlspecialchars(
                                                $project['participant_name']
                                                ?? 'Participant'
                                            ) ?>
                                        </div>

                                        <div class="small text-muted">
                                            <?= htmlspecialchars(
                                                $project['participant_email']
                                                ?? ''
                                            ) ?>
                                        </div>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php if (
                                        ($project['registration_type'] ?? null)
                                        === 'team'
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

                                    <?php if (
                                        !empty(
                                            $project['latest_submission_version']
                                        )
                                    ): ?>

                                        Version
                                        <?= (int) $project[
                                            'latest_submission_version'
                                        ] ?>

                                    <?php else: ?>

                                        <span class="text-muted">
                                            —
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <span class="badge bg-<?= $badgeClass ?>">
                                        <?= htmlspecialchars(
                                            projectStatusLabel(
                                                $submissionStatus
                                            )
                                        ) ?>
                                    </span>

                                </td>


                                <td>

                                    <?php if (
                                        !empty($project['updated_at'])
                                    ): ?>

                                        <?= htmlspecialchars(
                                            $project['updated_at']
                                        ) ?>

                                    <?php else: ?>

                                        <span class="text-muted">
                                            —
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td class="text-end">

                                    <a
                                        href="/TECHATHON/public/organizer/hackathons/<?= (int) $hackathon['id'] ?>/projects/<?= (int) $project['id'] ?>"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View Project
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

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>
</html>
