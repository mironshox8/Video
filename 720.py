import subprocess
import shlex

# === Parametrlar ===
video_path = "taqdimot.mp4"
output_path = "compressed_output_720p.mp4"

ffmpeg_path = r"D:\qirqish\ffmpeg-2025-08-04-git-9a32b86307-full_build\ffmpeg-2025-08-04-git-9a32b86307-full_build\bin\ffmpeg.exe"

# H.264 kodek bilan 720p qilib siqish
command = f'"{ffmpeg_path}" -i "{video_path}" -vf scale=-2:1080 -c:v libx264 -preset slow -crf 23 -c:a aac -b:a 128k "{output_path}" -progress - -nostats'

# === FFmpeg’ni ishga tushirish ===
process = subprocess.Popen(shlex.split(command), stdout=subprocess.PIPE, stderr=subprocess.STDOUT, text=True)

print("720p siqish jarayoni boshlandi...\n")

while True:
    output = process.stdout.readline()
    if output == '' and process.poll() is not None:
        break

    if "out_time_ms" in output:
        value = output.strip().split("=")[1]
        if value == "N/A":
            continue  # vaqt hali aniqlanmagan bo‘lsa, tashlab ketamiz
        try:
            time_ms = int(value)
            processed_sec = time_ms / 1_000_000
            print(f"\rJarayon davom etmoqda: {processed_sec:.0f} soniya qayta ishlanmoqda...", end="")
        except ValueError:
            continue  # boshqa noto‘g‘ri qiymatlar bo‘lsa ham tashlab ketamiz

process.wait()
print("\n✅ 720p siqish tugadi! Fayl:", output_path)
