<?php

$hackathonTitle = $hackathon['title'] ?? 'Hackathon';

$assignedCount = count($assignedJudges);
$availableCount = count($availableJudges);
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
        <?= htmlspecialchars($title ?? 'Judges') ?>
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
                Judges
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


    <?php if (!empty($successMessage)): ?>

        <div class="alert alert-success">
            <?= htmlspecialchars($successMessage) ?>
        </div>

    <?php endif; ?>


    <?php if (!empty($errorMessage)): ?>

        <div class="alert alert-danger">
            <?= htmlspecialchars($errorMessage) ?>
        </div>

    <?php endif; ?>


    <div class="row g-3 mb-4">

        <div class="col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Assigned Judges
                    </div>

                    <div class="fs-3 fw-bold">
                        <?= $assignedCount ?>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Available Judges
                    </div>

                    <div class="fs-3 fw-bold">
                        <?= $availableCount ?>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Assign Judge
            </h5>

        </div>

        <div class="card-body">

            <?php if (empty($availableJudges)): ?>

                <div class="alert alert-light mb-0">
                    There are no available judges to assign.
                </div>

            <?php else: ?>

                <form
                    method="POST"
                    action="/TECHATHON/public/organizer/hackathons/<?= (int) $hackathon['id'] ?>/judges/assign"
                >

                    <input
                        type="hidden"
                        name="_token"
                        value="<?= htmlspecialchars($csrfToken) ?>"
                    >

                    <div class="row g-3 align-items-end">

                        <div class="col-md-9">

                            <label
                                for="judge_id"
                                class="form-label"
                            >
                                Select Judge
                            </label>

                            <select
                                name="judge_id"
                                id="judge_id"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select a judge
                                </option>

                                <?php foreach (
                                    $availableJudges
                                    as $judge
                                ): ?>

                                    <option
                                        value="<?= (int) $judge['judge_id'] ?>"
                                    >
                                        <?= htmlspecialchars(
                                            $judge['name']
                                        ) ?>

                                        -
                                        <?= htmlspecialchars(
                                            $judge['email']
                                        ) ?>

                                        <?php if (
                                            !empty(
                                                $judge['expertise']
                                            )
                                        ): ?>

                                            -
                                            <?= htmlspecialchars(
                                                $judge['expertise']
                                            ) ?>

                                        <?php endif; ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <div class="col-md-3">

                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                            >
                                Assign Judge
                            </button>

                        </div>

                    </div>

                </form>

            <?php endif; ?>

        </div>

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Assigned Judges
            </h5>

        </div>


        <div class="card-body p-0">

            <?php if (empty($assignedJudges)): ?>

                <div class="text-center py-5 px-3">

                    <h5 class="mb-2">
                        No judges assigned
                    </h5>

                    <p class="text-muted mb-0">
                        Assign a judge using the form above.
                    </p>

                </div>

            <?php else: ?>

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>Judge</th>

                            <th>Email</th>

                            <th>Expertise</th>

                            <th>Evaluations</th>

                            <th>Assigned At</th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>

                        <tbody>

                        <?php foreach (
                            $assignedJudges
                            as $judge
                        ): ?>

                            <tr>

                                <td>

                                    <div class="fw-semibold">

                                        <?= htmlspecialchars(
                                            $judge['name']
                                        ) ?>

                                    </div>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $judge['email']
                                    ) ?>

                                </td>


                                <td>

                                    <?php if (
                                        !empty(
                                            $judge['expertise']
                                        )
                                    ): ?>

                                        <span
                                            class="badge bg-light text-dark"
                                        >
                                            <?= htmlspecialchars(
                                                $judge['expertise']
                                            ) ?>
                                        </span>

                                    <?php else: ?>

                                        <span class="text-muted">
                                            —
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php
                                    $evaluationCount =
                                        (int) (
                                            $judge[
                                                'evaluation_count'
                                            ] ?? 0
                                        );
                                    ?>

                                    <?php if (
                                        $evaluationCount > 0
                                    ): ?>

                                        <span
                                            class="badge bg-success"
                                        >
                                            <?= $evaluationCount ?>
                                        </span>

                                    <?php else: ?>

                                        <span
                                            class="badge bg-secondary"
                                        >
                                            0
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?= !empty(
                                        $judge['assigned_at']
                                    )
                                        ? htmlspecialchars(
                                            $judge['assigned_at']
                                        )
                                        : '—'
                                    ?>

                                </td>


                                <td class="text-end">

                                    <?php if (
                                        $evaluationCount > 0
                                    ): ?>

                                        <span
                                            class="text-muted small"
                                        >
                                            Evaluation history exists
                                        </span>

                                    <?php else: ?>

                                        <form
                                            method="POST"
                                            action="/TECHATHON/public/organizer/hackathons/<?= (int) $hackathon['id'] ?>/judges/<?= (int) $judge['assignment_id'] ?>/remove"
                                            class="d-inline"
                                            onsubmit="return confirm('Remove this judge from the hackathon?');"
                                        >

                                            <input
                                                type="hidden"
                                                name="_token"
                                                value="<?= htmlspecialchars(
                                                    $csrfToken
                                                ) ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                            >
                                                Remove
                                            </button>

                                        </form>

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

</body>

</html>