<?php
// Determine which date to load
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Validate date format
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    $date = date('Y-m-d');
}

$data_file = __DIR__ . '/data/brief-' . $date . '.json';

if (!file_exists($data_file)) {
    // No brief for this date — show a placeholder
    $brief = null;
} else {
    $brief = json_decode(file_get_contents($data_file), true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <title>Morning Brief<?php if ($brief): ?> — <?php echo htmlspecialchars($brief['display_date']); ?><?php endif; ?></title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=Space+Mono:wght@400;700&display=swap');
    :root { --bg: #dce8df; --black: #0e0e0e; --muted: #556055; --rule: #b0c4b4; }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Space Grotesk', sans-serif; background: var(--bg); color: var(--black); min-height: 100vh; }
    .masthead { padding: 52px 56px 44px; border-bottom: 2px solid var(--black); display: grid; grid-template-columns: 1fr auto; align-items: end; gap: 24px; }
    .masthead-wordmark { font-size: clamp(56px, 11vw, 108px); font-weight: 700; letter-spacing: -0.045em; line-height: 0.88; text-transform: uppercase; }
    .masthead-wordmark .dim { color: var(--muted); }
    .masthead-right { text-align: right; padding-bottom: 6px; }
    .masthead-date { font-family: 'Space Mono', monospace; font-size: 11px; line-height: 2; letter-spacing: 0.04em; color: var(--muted); text-transform: uppercase; }
    .snav { display: flex; overflow-x: auto; scrollbar-width: none; border-bottom: 1px solid var(--rule); }
    .snav::-webkit-scrollbar { display: none; }
    .snav a { font-family: 'Space Mono', monospace; font-size: 9.5px; letter-spacing: 0.12em; text-transform: uppercase; color: var(--muted); text-decoration: none; padding: 12px 18px; border-right: 1px solid var(--rule); white-space: nowrap; flex-shrink: 0; transition: background 0.12s, color 0.12s; }
    .snav a:hover { background: rgba(0,0,0,0.06); color: var(--black); }
    .content { max-width: 1060px; margin: 0 auto; padding: 0 56px 120px; }
    .greeting-bar { display: flex; flex-direction: column; gap: 5px; padding: 40px 0 14px; border-bottom: 1px solid var(--black); }
    .greeting-bar-subtitle { font-family: 'Space Mono', monospace; font-size: 9.5px; letter-spacing: 0.14em; text-transform: uppercase; color: var(--muted); }
    .greeting-bar-name { font-size: clamp(26px, 5vw, 48px); font-weight: 700; letter-spacing: -0.03em; text-transform: uppercase; line-height: 1; color: var(--black); }
    .through-line { display: grid; grid-template-columns: 180px 1fr; gap: 0 48px; padding: 36px 0; border-bottom: 1px solid var(--rule); align-items: start; }
    @media (max-width: 680px) { .through-line { grid-template-columns: 1fr; gap: 20px; } }
    .through-line-left { padding-top: 4px; display: flex; flex-direction: column; gap: 12px; }
    .through-line-label { font-family: 'Space Mono', monospace; font-size: 9px; letter-spacing: 0.14em; text-transform: uppercase; color: var(--muted); }
    .through-line-quote { font-family: 'Space Mono', monospace; font-size: 11.5px; line-height: 1.85; color: #2e3d2e; }
    .through-line-quote-attr { font-family: 'Space Mono', monospace; font-size: 9px; letter-spacing: 0.08em; text-transform: uppercase; color: var(--rule); margin-top: 8px; }
    .through-line-right { display: flex; flex-direction: column; gap: 14px; }
    .through-line-text { font-size: clamp(15px, 2vw, 18px); font-weight: 500; line-height: 1.65; letter-spacing: -0.01em; color: var(--black); }
    .section { border-bottom: 1px solid var(--rule); }
    .section-bar { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; padding: 40px 0 14px; border-bottom: 1px solid var(--black); }
    .section-bar-label { display: flex; flex-direction: column; gap: 5px; }
    .section-bar-subtitle { font-family: 'Space Mono', monospace; font-size: 9.5px; letter-spacing: 0.14em; text-transform: uppercase; color: var(--muted); }
    .section-bar-name { font-size: clamp(26px, 5vw, 48px); font-weight: 700; letter-spacing: -0.03em; text-transform: uppercase; line-height: 1; color: var(--black); }
    .section-bar-meta { font-family: 'Space Mono', monospace; font-size: 9px; letter-spacing: 0.12em; text-transform: uppercase; color: var(--muted); flex-shrink: 0; padding-bottom: 4px; }
    .article-row { display: grid; grid-template-columns: 180px 1fr; gap: 0 48px; padding: 36px 0; border-bottom: 1px solid var(--rule); }
    @media (max-width: 680px) { .article-row { grid-template-columns: 1fr; gap: 16px; } }
    .article-left { padding-top: 4px; }
    .article-source { font-family: 'Space Mono', monospace; font-size: 9px; letter-spacing: 0.1em; text-transform: uppercase; color: var(--muted); line-height: 1.8; }
    .article-right { display: flex; flex-direction: column; gap: 14px; }
    .article-title { font-size: clamp(18px, 2.8vw, 26px); font-weight: 700; letter-spacing: -0.025em; line-height: 1.15; }
    .article-title a { color: var(--black); text-decoration: none; }
    .article-title a:hover { text-decoration: underline; }
    .article-pitch { font-family: 'Space Mono', monospace; font-size: 11.5px; line-height: 1.85; color: #2e3d2e; max-width: 72ch; }
    .article-actions { display: flex; gap: 6px; align-items: center; margin-top: 4px; }
    .action-btn { background: none; border: 1px solid var(--rule); border-radius: 4px; padding: 4px 10px 4px 7px; cursor: pointer; color: var(--muted); display: inline-flex; align-items: center; gap: 5px; font-family: 'Space Mono', monospace; font-size: 9px; letter-spacing: 0.08em; text-transform: uppercase; transition: all 0.12s; }
    .action-btn:hover { background: rgba(0,0,0,0.06); color: var(--black); border-color: var(--muted); }
    .action-btn.active-up   { border-color: #4a7c59; color: #4a7c59; background: rgba(74,124,89,0.1); }
    .action-btn.active-down { border-color: #8b4a4a; color: #8b4a4a; background: rgba(139,74,74,0.1); }
    .action-btn.active-save { border-color: #4a5e7c; color: #4a5e7c; background: rgba(74,94,124,0.1); }
    .material-symbols-outlined { font-size: 14px; line-height: 1; }
    .footer { border-top: 2px solid var(--black); padding: 26px 56px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
    .footer-l { font-weight: 700; font-size: 11px; letter-spacing: 0.1em; text-transform: uppercase; }
    .footer-r { font-family: 'Space Mono', monospace; font-size: 10px; color: var(--muted); letter-spacing: 0.04em; }
    .no-brief { max-width: 1060px; margin: 80px auto; padding: 0 56px; font-family: 'Space Mono', monospace; font-size: 12px; color: var(--muted); }
    .feedback-toast { position: fixed; bottom: 28px; left: 50%; transform: translateX(-50%); background: var(--black); color: #dce8df; font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.08em; padding: 10px 20px; border-radius: 4px; opacity: 0; transition: opacity 0.2s; pointer-events: none; }
    .feedback-toast.show { opacity: 1; }
    @media (max-width: 680px) { .masthead { padding: 32px 24px 28px; } .content { padding: 0 24px 80px; } .section-bar { padding-top: 28px; } .footer { padding: 22px 24px; } }
  </style>
</head>
<body>

<div class="feedback-toast" id="toast"></div>

<header class="masthead">
  <div class="masthead-wordmark">Morning<br><span class="dim">Brief</span></div>
  <div class="masthead-right">
    <?php if ($brief): ?>
    <div class="masthead-date">
      <?php echo htmlspecialchars($brief['day_name']); ?><br>
      <?php echo htmlspecialchars($brief['display_date']); ?><br>
      <?php echo $brief['section_count']; ?> sections<br>
      <?php echo $brief['item_count']; ?> items
    </div>
    <?php else: ?>
    <div class="masthead-date"><?php echo htmlspecialchars($date); ?></div>
    <?php endif; ?>
  </div>
</header>

<?php if (!$brief): ?>
<div class="no-brief">No brief available for <?php echo htmlspecialchars($date); ?>.</div>
<?php else: ?>

<nav class="snav">
  <?php foreach ($brief['sections'] as $section): ?>
  <a href="#<?php echo htmlspecialchars($section['id']); ?>"><?php echo htmlspecialchars($section['name']); ?></a>
  <?php endforeach; ?>
</nav>

<div class="content">

  <div class="greeting-bar">
    <div class="greeting-bar-subtitle" id="greeting-day"></div>
    <div class="greeting-bar-name" id="greeting-text"></div>
  </div>

  <div class="through-line">
    <div class="through-line-left">
      <div class="through-line-label">Today's quote</div>
      <div class="through-line-quote"><?php echo htmlspecialchars($brief['quote']); ?></div>
      <div class="through-line-quote-attr"><?php echo htmlspecialchars($brief['quote_attr']); ?></div>
    </div>
    <div class="through-line-right">
      <div class="through-line-label">Today's thread</div>
      <div class="through-line-text"><?php echo htmlspecialchars($brief['through_line']); ?></div>
    </div>
  </div>

  <?php foreach ($brief['sections'] as $section): ?>
  <section class="section" id="<?php echo htmlspecialchars($section['id']); ?>">
    <div class="section-bar">
      <div class="section-bar-label">
        <div class="section-bar-subtitle"><?php echo htmlspecialchars($section['subtitle']); ?></div>
        <div class="section-bar-name"><?php echo htmlspecialchars($section['name']); ?></div>
      </div>
      <div class="section-bar-meta"><?php echo count($section['articles']); ?> article<?php echo count($section['articles']) > 1 ? 's' : ''; ?></div>
    </div>

    <?php foreach ($section['articles'] as $article): ?>
    <div class="article-row">
      <div class="article-left">
        <div class="article-source">
          <?php echo htmlspecialchars($article['source_line1']); ?>
          <?php if (!empty($article['source_line2'])): ?><br><?php echo htmlspecialchars($article['source_line2']); ?><?php endif; ?>
        </div>
      </div>
      <div class="article-right">
        <div class="article-title">
          <a href="<?php echo htmlspecialchars($article['url']); ?>" target="_blank"><?php echo htmlspecialchars($article['title']); ?></a>
        </div>
        <div class="article-pitch"><?php echo htmlspecialchars($article['pitch']); ?></div>
        <div class="article-actions">
          <button class="action-btn" onclick="sendFeedback(this,'thumbs_up',<?php echo htmlspecialchars(json_encode($article['url'])); ?>,<?php echo htmlspecialchars(json_encode($article['title'])); ?>)">
            <span class="material-symbols-outlined">thumb_up</span> Interesting
          </button>
          <button class="action-btn" onclick="sendFeedback(this,'thumbs_down',<?php echo htmlspecialchars(json_encode($article['url'])); ?>,<?php echo htmlspecialchars(json_encode($article['title'])); ?>)">
            <span class="material-symbols-outlined">thumb_down</span> Pass
          </button>
          <button class="action-btn" onclick="sendFeedback(this,'save',<?php echo htmlspecialchars(json_encode($article['url'])); ?>,<?php echo htmlspecialchars(json_encode($article['title'])); ?>)">
            <span class="material-symbols-outlined">bookmark_add</span> Save
          </button>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

  </section>
  <?php endforeach; ?>

</div>

<footer class="footer">
  <div class="footer-l">Morning Brief</div>
  <div class="footer-r"><?php echo htmlspecialchars($brief['day_name']); ?> — <?php echo htmlspecialchars($brief['display_date']); ?></div>
</footer>

<?php endif; ?>

<script>
  const GREETINGS = [
    "Good morning, Nuno.",
    "Ready for another day?",
    "Still curious? Good.",
    "Start here.",
    "Make it count.",
    "Something worth knowing.",
    "The world kept moving.",
    "Let's begin.",
    "A good morning for ideas.",
    "You showed up.",
    "Coffee first, then this.",
    "Not everything. Just the good stuff.",
    "Another day, another thread.",
    "Slow down. Read well.",
    "The brief is ready.",
    "Here's what's worth your time.",
    "Morning, curious mind.",
    "Today's worth showing up for.",
    "Let's see what's good.",
    "The reading starts now.",
  ];

  const DAYS = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
  const now = new Date();
  const dayOfYear = Math.floor((now - new Date(now.getFullYear(), 0, 0)) / 86400000);
  document.getElementById('greeting-day').textContent = DAYS[now.getDay()];
  document.getElementById('greeting-text').textContent = GREETINGS[dayOfYear % GREETINGS.length];

  const BRIEF_DATE = <?php echo json_encode($date); ?>;
  const articleState = {};

  function getState(url) {
    if (!articleState[url]) articleState[url] = { rating: null, saved: false };
    return articleState[url];
  }

  function sendFeedback(btn, action, url, title) {
    const row = btn.closest('.article-actions');
    const s = getState(url);
    let serverAction = action;

    if (action === 'thumbs_up' || action === 'thumbs_down') {
      if (s.rating === action) { serverAction = 'undo_rating'; s.rating = null; }
      else { s.rating = action; }
    } else if (action === 'save') {
      if (s.saved) { serverAction = 'undo_save'; s.saved = false; }
      else { s.saved = true; }
    }

    updateButtons(row, s);

    fetch('/morning-brief/feedback.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: serverAction, url, title, brief_date: BRIEF_DATE })
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        const msgs = { undo_rating: 'Feedback removed', undo_save: 'Removed from saved', save: 'Saved to knowledge inbox' };
        showToast(msgs[serverAction] || 'Feedback recorded');
      }
    })
    .catch(() => showToast('Something went wrong — try again'));
  }

  function updateButtons(row, s) {
    const [upBtn, downBtn, saveBtn] = row.querySelectorAll('.action-btn');
    [upBtn, downBtn, saveBtn].forEach(b => { b.classList.remove('active-up','active-down','active-save'); b.disabled = false; });
    if (s.rating === 'thumbs_up')   upBtn.classList.add('active-up');
    if (s.rating === 'thumbs_down') downBtn.classList.add('active-down');
    if (s.saved)                    saveBtn.classList.add('active-save');
  }

  function showToast(msg) {
    const toast = document.getElementById('toast');
    toast.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2200);
  }
</script>

</body>
</html>
