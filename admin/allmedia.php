<?php
include 'main.php';



// Approve the selected media
if (isset($_GET['approve'])) {
    $stmt = $pdo->prepare('UPDATE media SET approved = 1 WHERE id = ?');
    $stmt->execute([$_GET['approve']]);
    header('Location: allmedia.php');
    exit;
}

// Which columns the users can order by, add/remove from the array below.
$order_by_list = array('id', 'title', 'year', 'fnr', 'description');
// Order by which column if specified (default to id)
$order_by = isset($_GET['order_by']) && in_array($_GET['order_by'], $order_by_list) ? $_GET['order_by'] : 'id';
// Sort by ascending or descending if specified (default to ASC)
$order_sort = isset($_GET['order_sort']) && $_GET['order_sort'] == 'DESC' ? 'DESC' : 'ASC';


if (isset($_POST['viewCat'])) {
    // if ($_POST['viewCat'] !== 0) {

    setcookie("viewing_cat",  $_POST['viewCat'], time() + 86400);

    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = ? ');
    $stmt->bindParam(1, $_POST['viewCat'], PDO::PARAM_INT);
    $stmt->execute();
    $viewCat = $stmt->fetch(PDO::FETCH_ASSOC);
    $catTitle = $viewCat['title'];

    $stmt = $pdo->prepare('SELECT m.* FROM media m JOIN media_categories mc ON mc.media_id = m.id AND mc.category_id = ? WHERE m.type = "image" ORDER BY ' . $order_by . ' ' . $order_sort);

    $stmt->bindParam(1, $_POST['viewCat'], PDO::PARAM_INT);
    $stmt->execute();

    $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $countMedia = $stmt->rowCount();
} else {
    setcookie("viewing_cat", 0, time() + 86400);

    //TODO DEBUG when type is not image
    //  $stmt = $pdo->prepare('SELECT * FROM media  ORDER BY ' . $order_by . ' ' . $order_sort . ' LIMIT 20');
    $stmt = $pdo->prepare('SELECT * FROM media WHERE type = "image" ORDER BY ' . $order_by . ' ' . $order_sort . ' LIMIT 20');
    $stmt->execute();
    $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $countMedia = $stmt->rowCount();
}

if (isset($_COOKIE['viewing_cat'])) {
    if ($_COOKIE['viewing_cat'] > 0) {
        $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = ? ');
        $stmt->bindParam(1, $_COOKIE['viewing_cat'], PDO::PARAM_INT);
        $stmt->execute();
        $viewCat = $stmt->fetch(PDO::FETCH_ASSOC);
        $catTitle = $viewCat['title'];
        //todo allow for other media types ??
        $stmt = $pdo->prepare('SELECT m.* FROM media m JOIN media_categories mc ON mc.media_id = m.id AND mc.category_id = ? WHERE m.type = "image" ORDER BY ' . $order_by . ' ' . $order_sort);

        $stmt->bindParam(1, $_COOKIE['viewing_cat'], PDO::PARAM_INT);
        $stmt->execute();

        $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $countMedia = $stmt->rowCount();
    }
}


?>


<?= template_admin_header('Media Gallery\'s', 'MediaGallery') ?>
<section>
<!-- <form action="" method="post">
    <label class="my-custom-checkbox">
        <input type="checkbox" class="my-checkbox-input">
        <span class="my-checkbox-indicator"></span>
    </label>
</form> -->
<style>
    .my-custom-checkbox {
        --custom-color: #26a69a;
        position: relative;
    }

    .my-checkbox-input {
        display: none;
    }

    .my-checkbox-input:checked~.my-checkbox-indicator {
        background-color: var(--custom-color);
        border-color: var(--custom-color);
        background-size: 80%;
    }

    .my-checkbox-indicator {
        border-radius: 3px;
        display: inline-block;
        position: absolute;
        top: 4px;
        left: 0;
        width: 16px;
        height: 16px;
        border: 2px solid #aaa;
        transition: .3s;
        background: transparent;
        background-size: 0%;
        background-position: center;
        background-repeat: no-repeat;
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3E%3Cpath fill='%23fff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26 2.974 7.25 8 2.193z'/%3E%3C/svg%3E");
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const checkboxes = document.querySelectorAll('.my-checkbox-input');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const indicator = this.nextElementSibling;
                if (this.checked) {
                    indicator.style.backgroundSize = '80%';
                } else {
                    indicator.style.backgroundSize = '0%';
                }
            });
        });
    });
</script>

<h2>Viewing <?= (isset($catTitle)) ?
                $catTitle . " - " . $countMedia . " media files." :
                "all media." . " - " . $countMedia . " media files" ?>
</h2>


<div class="rj-table-ctrl">


    <form action="allmedia.php" method='post' class="bulkOptionContainer">
        <?php
        $stmt = $pdo->prepare('SELECT * FROM categories ORDER BY id DESC');
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <select class="form-control" name="viewCat" id="">
            <option value=0>Select Category</option>
            <?php foreach ($categories as $category) : ?>
                <option value=<?= $category['id'] ?>><?= $category['title'] ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" name="submit" class="btn btn-success" value="View Category">

    </form>

    <form action="" id="rj-table-search" class="bulkOptionContainer">
        <label for="search">Search Media</label>
        <input type="search" name="search" id="" class="form-control">
        <button class="btn">Search</button>
    </form>

</div>
<div class="rj-btn-grid">
    <a href="allmedia.php" class="btn btn-primary">View All Media</a>
    <button class="btn" id="qEditToggle">Enable Quickedit                                 <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-ballpen" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M14 6l7 7l-4 4" />
                                <path d="M5.828 18.172a2.828 2.828 0 0 0 4 0l10.586 -10.586a2 2 0 0 0 0 -2.829l-1.171 -1.171a2 2 0 0 0 -2.829 0l-10.586 10.586a2.828 2.828 0 0 0 0 4z" />
                                <path d="M4 20l1.768 -1.768" />
                                </svg></button>

    <a href="media.php" class="btn">Create Media</a>
</div>


<button class="btn" id="btn-details">Details</button>
<div class="table-container">
<div class="details-container">
        <table class="jostable details-table">
        
            <thead>
                <tr>
                    <th>
                        iets
                    </th>
                    <th>
                        iets
                    </th>
                    <th>
                        iets
                    </th>
                    <th>
                        iets
                    </th>
                </tr>
            </thead>
                <tbody>
                    <tr>
                        <td>
                            wat
                        </td>
                        <td>
                            wat
                        </td>
                        <td>
                            wat
                        </td>
                        <td>
                            wat
                        </td>
                    </tr>
                    <tr>
                        <td>
                            wat
                        </td>
                        <td>
                            wat
                        </td>
                        <td>
                            wat
                        </td>
                        <td>
                            wat
                        </td>
                    </tr>
                    <tr>
                        <td>
                            wat
                        </td>
                        <td>
                            wat
                        </td>
                        <td>
                            wat
                        </td>
                        <td>
                            wat
                        </td>
                    </tr>
                </tbody>
        </table>
    </div>
    <table class="jostable">
        <thead></thead>
            <tr>
                <th> <a href="allmedia.php?order_by=id&order_sort=<?= $order_sort == 'ASC' ? 'DESC' : 'ASC' ?>" class="th-link">
                        <p>ID
                            <i class="fa-solid fa-sort" class="i-th-link"></i>
                        </p>
                    </a></th>
                <th><a href="allmedia.php?order_by=title&order_sort=<?= $order_sort == 'ASC' ? 'DESC' : 'ASC' ?>" class="th-link">
                        <p>title
                            <i class="fa-solid fa-sort" class="th-link"></i>
                        </p>
                    </a></th>
    
                <th class="td-items"><a href="allmedia.php?order_by=year&order_sort=<?= $order_sort == 'ASC' ? 'DESC' : 'ASC' ?>" class="th-link">
                        <p>Year </p>
    
                        <p>fNr. <i class="fa-solid fa-sort" class="th-link"></i></p>
                        <p> Date </p>
                    </a></th>
                <!-- <th><a href="allmedia.php?order_by=fnr&order_sort=<?= $order_sort == 'ASC' ? 'DESC' : 'ASC' ?>" class="th-link">
                        fnr
                        <i class="fa-solid fa-sort"></i>
                    </a></th> -->
                <th class="responsive-hidden"><a href="allmedia.php?order_by=description&order_sort=<?= $order_sort == 'ASC' ? 'DESC' : 'ASC' ?>" class="th-link">
                        <p> Description
                            <i class="fa-solid fa-sort"></i>
                        </p>
                    </a></th>
    
                <!-- <th class="responsive-hidden">Type</th> -->
                <!-- <th>Approved</th> -->
                <!-- <th class="responsive-hidden">Date</th> -->
            <th class="th-hide">
                test123
            </th>
                <th>
                    <p>Actions</p>
                </th>

                
            </tr>
        </thead>
        <tbody>
            <?php if (empty($media)) : ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no recent media files</td>
                </tr>
            <?php else : ?>
                <?php foreach ($media as $key => $m) :
                    $short_title = (strlen($m['title']) >= 44) ?
                        "<span data-short-title=1>" . substr(htmlspecialchars($m['title'], ENT_QUOTES), 0, 44) . "<span class='rj-elips'>...</span>" . "</span>"
                        :
                        "<span data-short-title=0>" . $m['title'] . "</span>";
    
                    // $short_desc = substr(nl2br(htmlspecialchars($m['description'], ENT_QUOTES)), 0, 100) . "<span style='color: red'>...</span>";
                    $short_desc = substr(htmlspecialchars($m['description'], ENT_QUOTES), 0, 100);
                ?>
                    <tr>
                        <td>
                            <?php if ($m['type'] == 'image') : ?>
                                <a href="../view.php?id=<?= $m['id'] ?>" target="_blank" class="copy-link" id="copyLink<?= $key ?>">
                                    <img src="../<?= $m['filepath'] ?>" alt="<?= $m['title'] ?>" loading="lazy">
                                </a>
                                <span class="rj-tooltip" id="rj-tooltip<?= $key ?>">Copied!</span>
    
                            <?php endif; ?>
                        </td>
    
    
                        <td>
                            <span class="q-edit-text" data-titleSpan-id=<?= (int) $m['id'] ?>><?= $short_title ?></span>
                            <span class="edit-icon" data-id="<?= $m['id'] ?>" data-field="title" data-content="<?= $m['title'] ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-ballpen" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M14 6l7 7l-4 4" />
                                <path d="M5.828 18.172a2.828 2.828 0 0 0 4 0l10.586 -10.586a2 2 0 0 0 0 -2.829l-1.171 -1.171a2 2 0 0 0 -2.829 0l-10.586 10.586a2.828 2.828 0 0 0 0 4z" />
                                <path d="M4 20l1.768 -1.768" />
                                </svg>
                            </span>
                        </td>
                        <td>
                            <div class="td-items">
                                <div class="td-item"><?= $m['year'] ?></div>
                                <div class="td-item"><?= $m['fnr'] ?></div>
                                <div class="td-item"><small><?= date('d/m/y', strtotime($m['uploaded_date'])) ?></small></div>
                            </div>
                        </td>
                        <!-- <td></td> -->
    
                        <td class="responsive-hidden">
    
                            <?= (strlen($m['description']) >= 100) ?
                                "<span data-short-content=1>" . $short_desc . "<span class='rj-elips'>...</span>" . "</span>" :
                                "<span data-short-content=0>" . $m['description'] . "</span>"
                            ?>
                        </td>
                        <!-- view and media type removed  -->
                        <td class="td-hide">
                            <?= $m['id'] ?> - jos123
                        </td>
    
                        <td class="rj-action-td">
                            <a href="media.php?id=<?= $m['id'] ?>" class="btn btn--edit">Edit</a>
                            <a href="#" class="btn btn--del" onclick="deleteMediaModal(<?= $m['id'] ?>)">Delete</a>
                            <?php if (!$m['approved']) : ?>
                                <a href="allmedia.php?approve=<?= $m['id'] ?>">Approve</a>
                            <?php endif; ?>
    
                        </td>
    
                    </tr>

                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <!--  table that over lays primary table -->

</div>
</div>
</div>

<div class="delMediaModalWrap"></div>
<div class="editModalWrap"></div>

</section>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let quickEditEnabled = false
        let copyLinksEnabled = false

        let detailsTable = document.querySelector('.details-table')
        let detailsBtn = document.querySelector('#btn-details')

        detailsBtn.addEventListener("click" , () => {
            document.querySelector(".details-container").classList.toggle("container-visible")
            detailsTable.classList.toggle('table-visible')
        })


        // Get all elements with class="copy-link"
        const copyLinks = document.querySelectorAll('.copy-link');
        copyLinks.forEach((copyLink, index) => {
            event.preventDefault(); // Prevent the default action (navigation)
            if (quickEditEnabled) {
                copyLink.style.display = "block";
                copyLink.addEventListener('click', function(event) {


                    // Get the href attribute
                    const link = copyLink.getAttribute('href');

                    // Copy to clipboard
                    navigator.clipboard.writeText(link).then(() => {
                        // Show tooltip
                        //todo bug when null
                        if (link == null) {
                            link == "jemoederlink"
                        }
                        console.log(link)
                        const tooltip = document.getElementById(`rj-tooltip${index}`);
                        tooltip.classList.add('rj-tooltip-visible')

                        // Hide tooltip after 2 seconds
                        setTimeout(() => {
                            tooltip.classList.remove('rj-tooltip-visible')

                        }, 2000);
                    }).catch(err => {
                        console.error('Failed to copy text: ', err);
                    });
                })
            } else {
                // copyLink.style.display = "none";
            }

        });

        // quick edit
      
        const editIcons = document.querySelectorAll('.edit-icon');
        const qEditToggle = document.querySelector('#qEditToggle');
        let editBtnVisible = false;

        qEditToggle.addEventListener('click', () => {
            if (editBtnVisible == true) {

                editBtnVisible = false;
                editIcons.forEach(icon => {
                icon.style.display = "none"
            })
        } else {
                editBtnVisible = true;
                editIcons.forEach(icon => {
                icon.style.display="block"
                icon.addEventListener('click', () => {
                    const id = this.getAttribute('data-id');
                    const content = this.getAttribute('data-content');
                    const fieldUncap = this.getAttribute('data-field')
                    const editField = fieldUncap.charAt(0).toUpperCase() + fieldUncap.slice(1)
            
                    
                    openEditModal(id, content, editField);
                });
            }) 
            }
        })
        

        


        
    });

    function openEditModal(id, content, editField) {
        // populate modal content
        const editModalContent = `
        <label for="rj-modal" class="rj-modal-background"></label>
        <div class="rj-modal">
            <div class="rj-modal-header">
                <h3>Edit ${editField} - ID : ${id}</h3>
                <label for="rj-modal" class="close-button" onclick="closeEditModal()">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAdVBMVEUAAABNTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU0N3NIOAAAAJnRSTlMAAQIDBAUGBwgRFRYZGiEjQ3l7hYaqtLm8vsDFx87a4uvv8fP1+bbY9ZEAAAB8SURBVBhXXY5LFoJAAMOCIP4VBRXEv5j7H9HFDOizu2TRFljedgCQHeocWHVaAWStXnKyl2oVWI+kd1XLvFV1D7Ng3qrWKYMZ+MdEhk3gbhw59KvlH0eTnf2mgiRwvQ7NW6aqNmncukKhnvo/zzlQ2PR/HgsAJkncH6XwAcr0FUY5BVeFAAAAAElFTkSuQmCC" width="16" height="16" alt="&times;">
                </label>
            </div>
            <div class="rj-modal-body">
                <p><strong>Current: </strong> "${content}" </p>
                <input type="text" id="new${editField}" placeholder="New Title">
                <button onclick="quickEdit(${id}, '${content}', '${editField}')" class="rj-modal-btn">Edit</button>
                <button onclick="closeEditModal()" class="rj-modal-btn">Cancel</button>
            </div>
        </div>
    `;
        document.querySelector('.editModalWrap').innerHTML = editModalContent;
    }

    function closeEditModal() {
        document.querySelector('.editModalWrap').innerHTML = "";
    }

    function quickEdit(id, content, editField) {
        const newTitle = document.getElementById('newTitle').value;
        fetch(`q_edit_media.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id,
                    newTitle
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                 
                    const titleSpan = document.querySelector('[data-titlespan-id="'+id+'"]');
                    titleSpan.innerHTML = `<span class='q-edit-new-value'>${data.newTitle}</span>`
                    
                    // alert('Title updated successfully');
                } else {
                    alert('Failed to update title');
                }
              
            });
    }


    function toggle(source) {
        checkboxes = document.getElementsByName('checkBoxArray[]');
        for (var i = 0, n = checkboxes.length;; i++) {
            checkboxes[i].checked = source.checked;
        }
    }


    delMediaModalWrap = document.querySelector('.delMediaModalWrap');

    let delModalMediaContent = `<label for="rj-modal" class="rj-modal-background"></label>
          <div class="rj-modal">
          <div class="rj-modal-header">
          <h3>Confirm deletion</h3>
            <label for="rj-modal">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAdVBMVEUAAABNTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU0N3NIOAAAAJnRSTlMAAQIDBAUGBwgRFRYZGiEjQ3l7hYaqtLm8vsDFx87a4uvv8fP1+bbY9ZEAAAB8SURBVBhXXY5LFoJAAMOCIP4VBRXEv5j7H9HFDOizu2TRFljedgCQHeocWHVaAWStXnKyl2oVWI+kd1XLvFV1D7Ng3qrWKYMZ+MdEhk3gbhw59KvlH0eTnf2mgiRwvQ7NW6aqNmncukKhnvo/zzlQ2PR/HgsAJkncH6XwAcr0FUY5BVeFAAAAAElFTkSuQmCC" width="16" height="16" alt="" onclick="closeDelModal()">
            </label>
            </div>
            <p>
            Delete Media?<br>
            `;


    function deleteMediaModal(id) {
        let media_id = id;
        let link = `<a href="allmedia.php?delete=` + media_id + `" class="rj-modal-btn">Confirm</a><br><a href="allmedia.php" onClick="closeDelMediaModal()" class="rj-modal-btn">Cancel</a> </p></div>`
        document.querySelector(".delMediaModalWrap").innerHTML = delModalMediaContent + link;
    }


    function closeDelMediaModal() {
        delModal.style.display = "none";

    }
</script>


<?= template_admin_footer() ?>