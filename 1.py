import cv2
import sys

# 🔸 Kirish videosi
video_path = "dog.mp4"

# 🔸 Boshlanish va tugash vaqtlari (sekundlarda)
start_time = 0       # 5 soniyadan boshlab
end_time = 114    # 15 soniyagacha

# 🔸 Video ochamiz
cap = cv2.VideoCapture(video_path)
fps = cap.get(cv2.CAP_PROP_FPS)
width = int(cap.get(cv2.CAP_PROP_FRAME_WIDTH))
height = int(cap.get(cv2.CAP_PROP_FRAME_HEIGHT))

# 🔸 Saqlash uchun VideoWriter
fourcc = cv2.VideoWriter_fourcc(*'mp4v')
out = cv2.VideoWriter('cut_output.mp4', fourcc, fps, (width, height))

# 🔸 Boshlanish va tugash kadr raqamlari
start_frame = int(start_time * fps)
end_frame = int(end_time * fps)
total_frames = end_frame - start_frame

# 🔸 Videoni kiritilgan joydan boshlaymiz
cap.set(cv2.CAP_PROP_POS_FRAMES, start_frame)

# 🔸 Kesish
while cap.isOpened():
    current_frame = int(cap.get(cv2.CAP_PROP_POS_FRAMES))

    if current_frame > end_frame:
        break

    ret, frame = cap.read()
    if not ret:
        break

    out.write(frame)

    # 📊 Foiz hisoblash
    processed_frames = current_frame - start_frame
    progress = (processed_frames / total_frames) * 100

    # 🔹 Terminalda progressni ko‘rsatish
    sys.stdout.write(f"\rJarayon: {progress:.2f}% bajarildi")
    sys.stdout.flush()

    if cv2.waitKey(1) & 0xFF == 27:
        break

cap.release()
out.release()
cv2.destroyAllWindows()

print("\n✅ Kesish jarayoni tugadi!")
