<!--
*
* display buttons for pagination
*
*-->
<form class="align-center" method="get">
    <button type="submit" name="currentPage" value="<?= Static::getPage() - 1; ?>"><< Prev</button>
    <span> - Page: <?= Static::getPage(); ?> - </span>
    <button type="submit" name="currentPage" value="<?= Static::getPage() + 1 ?>">Next >></button>
</form>