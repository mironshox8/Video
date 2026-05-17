<?php
   session_start(); // Sessiyani boshlash
   include 'config.php';
   if (!isset($_SESSION['admin'])) {
       header("Location: index.php");
       exit();
   }

   ?>
<?php
include 'header.php';
?>

<?php

$servername = "localhost";
$username = "suniyin2_avloniy";
$password = "+998973004959rammi";
$dbname = "suniyin2_avloniy";

// Ulanishni yaratish
$conn = new mysqli($servername, $username, $password, $dbname);

// Ulanishni tekshirish
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Foydalanuvchi ID sini o'rnating (misol uchun)
$user_id = $_SESSION['id'];

// SQL so'rov - berilgan user_id uchun natijalarni olish
$sql = "SELECT mavzu, pass_status FROM natija WHERE user_id = $user_id";
$result = $conn->query($sql);

$courses = [
    ["id"=>1, "title"=>"Kirish: AI/ML/DL/CV", "done"=>false],
    ["id"=>2, "title"=>"Proyektlar sikli (5 bosqich)", "done"=>false],
    ["id"=>3, "title"=>"AI va Drawing", "done"=>false],
    ["id"=>4, "title"=>"AI va Atrof-muhit", "done"=>false],
    ["id"=>5, "title"=>"AI va Harakat", "done"=>false],
    ["id"=>6, "title"=>"AI va DeepFakes", "done"=>false],
    ["id"=>7, "title"=>"Python sintaksislari", "done"=>false],
    ["id"=>8, "title"=>"O‘zgaruvchilar va ma’lumot turlari", "done"=>false],
    ["id"=>9, "title"=>"If-else va tarmoqlanish", "done"=>false],
    ["id"=>10, "title"=>"For va While aylanmalar", "done"=>false],
    ["id"=>11, "title"=>"Funksiyalar", "done"=>false],
    ["id"=>12, "title"=>"Data Science & metodologiya", "done"=>false],
    ["id"=>13, "title"=>"Data Analysis", "done"=>false],
    ["id"=>14, "title"=>"Machine Learning", "done"=>false],
    ["id"=>15, "title"=>"Supervised ML", "done"=>false],
    ["id"=>16, "title"=>"Unsupervised ML", "done"=>false],
    ["id"=>17, "title"=>"Deep Learning & Neural Net", "done"=>false],
    ["id"=>18, "title"=>"Image Processing", "done"=>false],
    ["id"=>19, "title"=>"Image Processing(Amaliy)", "done"=>false],
    ["id"=>20, "title"=>"Computer Vision", "done"=>false],
    ["id"=>21, "title"=>"Face Recognition", "done"=>false],
    ["id"=>22, "title"=>"Face Recognition(Amaliy)", "done"=>false],
    ["id"=>23, "title"=>"OpenCV mashg‘ulotlari", "done"=>false],
    ["id"=>24, "title"=>"Color Detection", "done"=>false],
    ["id"=>25, "title"=>"Loyiha: Yuzni tanish", "done"=>false],
];;

// Agar natijalar mavjud bo'lsa
if ($result->num_rows > 0) {
    // Har bir natijani qayta ishlash
    while($row = $result->fetch_assoc()) {
        // Mavzu nomidan raqamni ajratib olish (masalan, "1-mavzu" dan 1 ni olish)
        $topic_number = (int) explode('-', $row['mavzu'])[0];
        
        // Agar mavzu raqami kurslar massivi doirasida bo'lsa va test o'tilgan bo'lsa
        if ($topic_number >= 1 && $topic_number <= count($courses) && $row['pass_status'] == 'passed') {
            // Kursni bajarilgan deb belgilash
            $courses[$topic_number-1]['done'] = true;
        }
    }
} else {
    echo "Hech qanday natija topilmadi";
}

// Ulanishni yopish
$conn->close();
?>


<!DOCTYPE html>
<html lang="uz">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>O'zlashtirgan kurslaringiz:</title>
<style>
  body.light-dashboard{
    background:#f7f9fc;
    font-family:"Segoe UI", Roboto, sans-serif;
    color:#1a1a1a;
    margin:0; padding:20px;
  }
  .wrap{max-width:1000px;margin:auto}
  .card{
    background:#fff;
    border-radius:20px;
    padding:20px;
    box-shadow:0 8px 25px rgba(0,0,0,.08);
    margin-bottom:20px;
  }
  .title{font-size:26px;font-weight:700;margin-bottom:12px}
  .stats{display:flex;gap:12px;flex-wrap:wrap}
  .stat{background:#f0f4fa;padding:10px 16px;border-radius:12px;font-weight:600}
  .progressbar{position:relative;height:12px;background:#e0e7f1;border-radius:999px;margin:12px 0;overflow:hidden}
  .progressfill{position:absolute;inset:0;width:0%;
    background:linear-gradient(90deg,#19c37d,#3ee0a0,#19c37d);
    background-size:200% 100%;
    animation:shine 3s linear infinite;
    transition:width 1s ease}
  @keyframes shine{0%{background-position:0% 0}100%{background-position:200% 0}}
  .percent{font-weight:700;color:#555}
  /* SVG dizayn */
  .svgbox{width:100%;aspect-ratio:16/7}
  svg{width:100%;height:100%}
  .snake-track{fill:none;stroke:#e4ebf5;stroke-width:16;stroke-linecap:round;stroke-linejoin:round}
  .snake-progress{fill:none;stroke:#19c37d;stroke-width:16;stroke-linecap:round;stroke-linejoin:round;
    filter:drop-shadow(0 0 6px rgba(25,195,125,.5));
    stroke-dasharray:5,10;
    animation:move 2s linear infinite}
  @keyframes move{to{stroke-dashoffset:-30}}
  .node circle{r:8}
  .node.done circle{fill:#19c37d;animation:pulse 1.8s infinite}
  .node.todo circle{fill:#ccc}
  .node text{font-size:11px;font-weight:600;fill:#333}
  @keyframes pulse{0%{transform:scale(1)}50%{transform:scale(1.3)}100%{transform:scale(1)}}
</style>
</head>
<body class="light-dashboard">
<div class="wrap">
  <div class="card">
    <div class="title">O'zlashtirgan kurslaringiz:</div>
    <div class="stats" id="stats"></div>
    <div class="progressbar"><div class="progressfill" id="progressfill"></div></div>
    <div class="percent" id="percent">0%</div>
  </div>

  <div class="card">
    <div class="svgbox">
      <svg viewBox="0 0 1200 520" xmlns="http://www.w3.org/2000/svg">
        <path id="snakePath" class="snake-track"
              d="M60,60 H1140
                 C1165,60 1165,100 1140,100 H60
                 C35,100 35,140 60,140 H1140
                 C1165,140 1165,180 1140,180 H60
                 C35,180 35,220 60,220 H1140
                 C1165,220 1165,260 1140,260 H60
                 C35,260 35,300 60,300 H1140
                 C1165,300 1165,340 1140,340 H60
                 C35,340 35,380 60,380 H1140
                 C1165,380 1165,420 1140,420 H60
                 C35,420 35,460 60,460 H1140" />
        <path id="snakeProgress" class="snake-progress" d="" />
        <g id="nodes"></g>
      </svg>
    </div>
  </div>
</div>
<script>
const COURSES = <?php echo json_encode($courses,JSON_UNESCAPED_UNICODE); ?>;
(function init(){
  const total = COURSES.length;
  const done = COURSES.filter(c=>c.done).length;
  const left = total-done;
  document.getElementById("stats").innerHTML =
    `<div class="stat">Tamomlangan: ${done}</div>
     <div class="stat">Qolgan: ${left}</div>
     <div class="stat">Jami: ${total}</div>`;
  const percent = total? Math.round(done/total*100):0;
  document.getElementById("percent").textContent=percent+"%";
  requestAnimationFrame(()=>document.getElementById("progressfill").style.width=percent+"%");

  const path=document.getElementById("snakePath");
  const prog=document.getElementById("snakeProgress");
  const nodes=document.getElementById("nodes");
  const L=path.getTotalLength();
  const progressLen=L*(done/Math.max(total,1));
  let d="";
  const seg=150;
  for(let i=0;i<=seg;i++){
    const dist=progressLen/seg*i;
    const p=path.getPointAtLength(dist);
    d+=(i==0?"M":"L")+p.x+","+p.y+" ";
  }
  prog.setAttribute("d",d.trim());

  const margin=40, step=(L-margin*2)/Math.max(total-1,1);
  COURSES.forEach((c,i)=>{
    const pt=path.getPointAtLength(margin+step*i);
    const g=document.createElementNS("http://www.w3.org/2000/svg","g");
    g.setAttribute("class","node "+(c.done?"done":"todo"));
    g.setAttribute("transform",`translate(${pt.x},${pt.y})`);
    const dot=document.createElementNS("http://www.w3.org/2000/svg","circle");
    const label=document.createElementNS("http://www.w3.org/2000/svg","text");
    label.setAttribute("y",(i%2?20:-16));
    label.setAttribute("text-anchor","middle");
    label.textContent=c.title;
    g.appendChild(dot); g.appendChild(label); nodes.appendChild(g);
  });
})();
</script>
</body>
</html>


                 
                      
                      
                      
                   
    
<?php
include 'footer.php';
?>                