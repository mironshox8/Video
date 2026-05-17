import subprocess
import shlex

# === Parametrlar ===
video_path = "7.mp4"

# Boshlanish va tugash vaqtlarini millisekundgacha aniqlash
start_time = "00:00:03.500"   # 5.500 sekunddan
end_time   = "00:02:40.000"   # 15.250 sekundgacha

output_path = "cut_output_with_audio_ms-6.24.mp4"

# HH:MM:SS.mmm formatini sekundlarga aylantirish (progress hisoblash uchun)
def time_to_seconds(time_str):
    h, m, s = time_str.split(":")
    s, ms = s.split(".")
    return int(h) * 3600 + int(m) * 60 + int(s) + int(ms) / 1000

total_duration = time_to_seconds(end_time) - time_to_seconds(start_time)

ffmpeg_path = r"D:\qirqish\ffmpeg-2025-08-04-git-9a32b86307-full_build\ffmpeg-2025-08-04-git-9a32b86307-full_build\bin\ffmpeg.exe"  # Sizning ffmpeg.exe manzilingiz
command = f'"{ffmpeg_path}" -ss {start_time} -to {end_time} -i "{video_path}" -c copy "{output_path}" -progress - -nostats'

# === FFmpeg’ni ishga tushirish ===
process = subprocess.Popen(shlex.split(command), stdout=subprocess.PIPE, stderr=subprocess.STDOUT, text=True)

print("Kesish jarayoni boshlandi...\n")

while True:
    output = process.stdout.readline()
    if output == '' and process.poll() is not None:
        break

    if "out_time_ms" in output:
        # FFmpegdan o‘tgan vaqtni olamiz
        time_ms = int(output.strip().split("=")[1])
        processed_sec = time_ms / 1_000_000
        progress = (processed_sec / total_duration) * 100
        print(f"\rJarayon: {progress:.2f}%", end="")

process.wait()
print("\n✅ Kesish tugadi! Fayl:", output_path)
