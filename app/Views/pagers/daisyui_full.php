<div class="flex justify-center w-full">
    <div class="join shadow-sm border border-base-300">
        <?php if ($pager->hasPrevious()) : ?>
            <a href="<?= $pager->getPrevious() ?>" aria-label="Previous" class="join-item btn btn-sm bg-base-100 hover:bg-base-200 border-none px-4">«</a>
        <?php else: ?>
            <button class="join-item btn btn-sm btn-disabled opacity-50 bg-base-100 border-none px-4 text-xs italic">«</button>
        <?php endif ?>

        <button class="join-item btn btn-sm bg-base-100 border-none px-6 font-bold cursor-default hover:bg-base-100">
            Page <?= $pager->getCurrentPageNumber() ?>
        </button>

        <?php if ($pager->hasNext()) : ?>
            <a href="<?= $pager->getNext() ?>" aria-label="Next" class="join-item btn btn-sm bg-base-100 hover:bg-base-200 border-none px-4">»</a>
        <?php else: ?>
            <button class="join-item btn btn-sm btn-disabled opacity-50 bg-base-100 border-none px-4 text-xs italic">»</button>
        <?php endif ?>
    </div>
</div>
