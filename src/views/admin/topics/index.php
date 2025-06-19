<h1>Topics & Subtopics</h1>
<a href="?page=admin&section=topics&action=create">+ Add Topic</a>
<style>
    .topic-level-0 { background: #f8f9fa; }
    .topic-level-1 { background: #e3f2fd; }
    .topic-level-2 { background: #fff3e0; }
    .topic-level-3 { background: #fce4ec; }
    .topic-level-4 { background: #ede7f6; }
    .topic-title { font-weight: bold; }
</style>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Title</th>
        <th>Course</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>
    <?php
    // Build a tree of topics by course and parent_topic_id
    $tree = [];
    foreach ($topics as $topic) {
        $tree[$topic['course_id']][$topic['parent_topic_id']][] = $topic;
    }
    function renderStaticTopicTree($tree, $course_id, $parent_id = null, $level = 0) {
        if (!isset($tree[$course_id][$parent_id])) return;
        foreach ($tree[$course_id][$parent_id] as $topic) {
            $levelClass = 'topic-level-' . min($level, 4);
            echo '<tr class="' . $levelClass . '">';
            echo '<td style="padding-left:' . (20 * $level) . 'px"><span class="topic-title">' . htmlspecialchars($topic['title']) . '</span></td>';
            echo '<td>' . htmlspecialchars($topic['course_title']) . '</td>';
            echo '<td>' . htmlspecialchars($topic['description']) . '</td>';
            echo '<td>';
            echo '<a href="?page=admin&section=topics&action=edit&id=' . $topic['id'] . '">Edit</a> | ';
            echo '<a href="?page=admin&section=topics&action=delete&id=' . $topic['id'] . '" onclick="return confirm(\'Delete this topic?\')">Delete</a>';
            echo '</td>';
            echo '</tr>';
            renderStaticTopicTree($tree, $course_id, $topic['id'], $level + 1);
        }
    }
    // Render topics grouped by course
    foreach ($tree as $course_id => $byParent) {
        renderStaticTopicTree($tree, $course_id, null, 0);
    }
    ?>
</table> 