<?php

use App\Core\Csrf;
use App\Core\Session;

$statistics = $statistics ?? [];

$hackathons = $hackathons ?? [];

$csrfToken = $csrfToken ?? Csrf::token();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($title ?? 'Organizer Dashboard') ?> - TECHATHON</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-4">

        <!-- ============================================================
         PAGE HEADER
         ============================================================ -->

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

            <div>
                <h1 class="mb-1">
                    Organizer Dashboard
                </h1>

                <p class="text-muted mb-0">
                    Manage your hackathons and organizer activities.
                </p>
            </div>

            <div class="mt-3 mt-md-0">

                <a href="/TECHATHON/public/organizer/hackathons/create" class="btn btn-primary">
                    Create Hackathon
                </a>

            </div>

        </div>


        <!-- ============================================================
         FLASH MESSAGES
         ============================================================ -->

        <?php if ($success = Session::getFlash('success')): ?>

            <div class="alert alert-success alert-dismissible fade show">

                <?= htmlspecialchars($success) ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

            </div>

        <?php endif; ?>


        <?php if ($error = Session::getFlash('error')): ?>

            <div class="alert alert-danger alert-dismissible fade show">

                <?= htmlspecialchars($error) ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

            </div>

        <?php endif; ?>


        <!-- ============================================================
         STATISTICS
         ============================================================ -->

        <div class="row g-3 mb-4">

            <div class="col-md-6 col-xl-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <p class="text-muted mb-1">
                            Total Hackathons
                        </p>

                        <h2 class="mb-0">
                            <?= (int) ($statistics['total_hackathons'] ?? 0) ?>
                        </h2>

                    </div>

                </div>

            </div>


            <div class="col-md-6 col-xl-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <p class="text-muted mb-1">
                            Drafts
                        </p>

                        <h2 class="mb-0">
                            <?= (int) ($statistics['draft_hackathons'] ?? 0) ?>
                        </h2>

                    </div>

                </div>

            </div>


            <div class="col-md-6 col-xl-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <p class="text-muted mb-1">
                            Pending Approval
                        </p>

                        <h2 class="mb-0">
                            <?= (int) ($statistics['pending_hackathons'] ?? 0) ?>
                        </h2>

                    </div>

                </div>

            </div>


            <div class="col-md-6 col-xl-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <p class="text-muted mb-1">
                            Approved
                        </p>

                        <h2 class="mb-0">
                            <?= (int) ($statistics['approved_hackathons'] ?? 0) ?>
                        </h2>

                    </div>

                </div>

            </div>

        </div>


        <!-- ============================================================
         HACKATHONS
         ============================================================ -->

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h4 class="mb-1">
                            My Hackathons
                        </h4>

                        <p class="text-muted mb-0">
                            View and manage your hackathons.
                        </p>

                    </div>

                    <span class="badge bg-light text-dark">
                        <?= count($hackathons) ?> total
                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                <?php if (empty($hackathons)): ?>

                    <div class="text-center py-5">

                        <h5 class="mb-2">
                            No hackathons yet
                        </h5>

                        <p class="text-muted mb-3">
                            Create your first hackathon to get started.
                        </p>

                        <a href="/TECHATHON/public/organizer/hackathons/create" class="btn btn-primary">
                            Create Hackathon
                        </a>

                    </div>

                <?php else: ?>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>
                                        Hackathon
                                    </th>

                                    <th>
                                        Category
                                    </th>

                                    <th>
                                        Participation
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Created
                                    </th>

                                    <th class="text-end">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <?php foreach ($hackathons as $hackathon): ?>

                                    <?php

                                    $status =
                                        strtolower(
                                            (string) ($hackathon['status'] ?? 'draft')
                                        );

                                    $createdAt = null;

                                    if (!empty($hackathon['created_at'])) {

                                        try {

                                            $createdAt =
                                                new DateTimeImmutable(
                                                    $hackathon['created_at']
                                                );

                                        } catch (Exception $exception) {

                                            $createdAt = null;

                                        }

                                    }


                                    /*
                                     * Organizer can edit ONLY when:
                                     *
                                     * 1. Hackathon is still a draft
                                     * 2. It is within the first 24 hours
                                     *
                                     * Once submitted for approval, status becomes
                                     * pending_approval and editing is locked.
                                     */

                                    $editDeadline = null;
                                    $canEdit = false;

                                    if ($createdAt !== null) {

                                        $editDeadline =
                                            $createdAt->modify('+24 hours');

                                        $now =
                                            new DateTimeImmutable();

                                        $canEdit =
                                            $status === 'draft'
                                            && $now <= $editDeadline;

                                    }


                                    /*
                                     * Status badge
                                     */

                                    $statusClass = 'secondary';

                                    switch ($status) {

                                        case 'draft':
                                            $statusClass = 'secondary';
                                            break;

                                        case 'pending_approval':
                                            $statusClass = 'warning';
                                            break;

                                        case 'approved':
                                            $statusClass = 'success';
                                            break;

                                        case 'registration_open':
                                            $statusClass = 'primary';
                                            break;

                                        case 'registration_closed':
                                            $statusClass = 'dark';
                                            break;

                                        case 'ongoing':
                                            $statusClass = 'info';
                                            break;

                                        case 'submission_closed':
                                            $statusClass = 'dark';
                                            break;

                                        case 'judging':
                                            $statusClass = 'primary';
                                            break;

                                        case 'results_published':
                                            $statusClass = 'success';
                                            break;

                                        case 'completed':
                                            $statusClass = 'success';
                                            break;

                                    }


                                    $statusLabel =
                                        ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $status
                                            )
                                        );

                                    ?>


                                    <tr>

                                        <!-- ====================================================
                                         TITLE
                                         ==================================================== -->

                                        <td>

                                            <div class="fw-semibold">

                                                <?= htmlspecialchars(
                                                    $hackathon['title'] ?? ''
                                                ) ?>

                                            </div>

                                            <?php if (!empty($hackathon['slug'])): ?>

                                                <small class="text-muted">

                                                    /
                                                    <?= htmlspecialchars(
                                                        $hackathon['slug']
                                                    ) ?>

                                                </small>

                                            <?php endif; ?>

                                        </td>


                                        <!-- ====================================================
                                         CATEGORY
                                         ==================================================== -->

                                        <td>

                                            <?= htmlspecialchars(
                                                $hackathon['category_name']
                                                ?? '—'
                                            ) ?>

                                        </td>


                                        <!-- ====================================================
                                         PARTICIPATION TYPE
                                         ==================================================== -->

                                        <td>

                                            <?php

                                            $participationType =
                                                $hackathon['participation_type']
                                                ?? 'individual';

                                            $participationLabel =
                                                ucwords(
                                                    $participationType
                                                );

                                            ?>

                                            <span class="badge bg-light text-dark">

                                                <?= htmlspecialchars(
                                                    $participationLabel
                                                ) ?>

                                            </span>

                                        </td>


                                        <!-- ====================================================
                                         STATUS
                                         ==================================================== -->

                                        <td>

                                            <span class="badge bg-<?= htmlspecialchars(
                                                $statusClass
                                            ) ?>">

                                                <?= htmlspecialchars(
                                                    $statusLabel
                                                ) ?>

                                            </span>

                                        </td>


                                        <!-- ====================================================
                                         CREATED
                                         ==================================================== -->

                                        <td>

                                            <?php if ($createdAt !== null): ?>

                                                <div>
                                                    <?= htmlspecialchars(
                                                        $createdAt->format(
                                                            'd M Y'
                                                        )
                                                    ) ?>
                                                </div>

                                                <small class="text-muted">
                                                    <?= htmlspecialchars(
                                                        $createdAt->format(
                                                            'h:i A'
                                                        )
                                                    ) ?>
                                                </small>

                                            <?php else: ?>

                                                —

                                            <?php endif; ?>

                                        </td>


                                        <!-- ====================================================
                                         ACTIONS
                                         ==================================================== -->

                                        <td class="text-end">

                                            <div class="d-flex justify-content-end gap-2 flex-wrap">


                                                <!-- ============================================
                                                 EDIT
                                                 ============================================ -->

                                                <?php if ($canEdit): ?>

                                                    <a href="/TECHATHON/public/organizer/hackathons/<?= (int) $hackathon['id'] ?>/edit"
                                                        class="btn btn-sm btn-primary">
                                                        Edit
                                                    </a>

                                                <?php endif; ?>


                                                <!-- ============================================
                                                 SUBMIT FOR APPROVAL
                                                 ============================================ -->

                                                <?php if ($status === 'draft'): ?>

                                                    <form method="POST"
                                                        action="/TECHATHON/public/organizer/hackathons/<?= (int) $hackathon['id'] ?>/submit"
                                                        class="d-inline">

                                                        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars(
                                                            $csrfToken
                                                        ) ?>">

                                                        <button type="submit" class="btn btn-sm btn-success"
                                                            onclick="return confirm('Submit this hackathon for admin approval? You will not be able to edit it after submission.');">
                                                            Submit for Approval
                                                        </button>

                                                    </form>

                                                <?php endif; ?>


                                                <!-- ============================================
                                                 MANAGE
                                                 ============================================ -->

                                                <a href="/TECHATHON/public/organizer/hackathons/<?= (int) $hackathon['id'] ?>/manage"
                                                    class="btn btn-sm btn-outline-primary">
                                                    Manage
                                                </a>

                                            </div>


                                            <!-- ================================================
                                             EDITING INFORMATION
                                             ================================================ -->

                                            <?php if (
                                                $status === 'draft'
                                                && $canEdit
                                                && $editDeadline !== null
                                            ): ?>

                                                <div class="small text-muted mt-2">

                                                    You can edit this hackathon until
                                                    <?= htmlspecialchars(
                                                        $editDeadline->format(
                                                            'd M Y, h:i A'
                                                        )
                                                    ) ?>.

                                                </div>


                                            <?php elseif ($status === 'draft'): ?>

                                                <div class="small text-warning mt-2">

                                                    Editing locked:
                                                    the 24-hour editing period has expired.

                                                </div>


                                            <?php elseif ($status === 'pending_approval'): ?>

                                                <div class="small text-info mt-2">

                                                    Editing locked:
                                                    this hackathon has been submitted
                                                    for admin approval.

                                                </div>


                                            <?php elseif ($status !== 'draft'): ?>

                                                <div class="small text-muted mt-2">

                                                    Editing locked:
                                                    this hackathon is no longer a draft.

                                                </div>

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>