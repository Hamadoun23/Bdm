"""Regénère la section 13 (prompts intégraux) dans bdm_v1.md depuis les transcripts Cursor."""

import glob
import json
import os
from datetime import datetime
from pathlib import Path

ROOT = Path(r"c:\xampp\htdocs\BDM")
V1 = ROOT / "bdm_v1.md"
TRANSCRIPTS = Path(r"C:\Users\cisse\.cursor\projects\c-xampp-htdocs-BDM\agent-transcripts")

MARKER_13 = "## 13. Journal complet des prompts"
MARKER_14 = "## 14. Prompt starter pour une nouvelle app"


def load_sessions():
    sessions = []
    for path in sorted(glob.glob(str(TRANSCRIPTS / "**" / "*.jsonl"), recursive=True)):
        if "subagents" in path.replace("\\", "/"):
            continue
        sid = os.path.basename(path).replace(".jsonl", "")
        mtime = os.path.getmtime(path)
        prompts = []
        with open(path, encoding="utf-8") as f:
            for line in f:
                try:
                    obj = json.loads(line)
                except json.JSONDecodeError:
                    continue
                if obj.get("role") != "user":
                    continue
                text = ""
                for part in obj.get("message", {}).get("content", []):
                    if part.get("type") == "text":
                        text += part.get("text", "")
                if "<user_query>" not in text:
                    continue
                q = text.split("<user_query>")[1].split("</user_query>")[0].strip()
                has_image = "[Image]" in text or "<image_files>" in text
                prompts.append({"text": q, "has_image": has_image})
        if prompts:
            sessions.append({"id": sid, "mtime": mtime, "prompts": prompts})
    sessions.sort(key=lambda s: s["mtime"])
    return sessions


def build_prompts_body(sessions):
    lines = []
    for si, sess in enumerate(sessions, 1):
        dt = datetime.fromtimestamp(sess["mtime"]).strftime("%Y-%m-%d %H:%M")
        sid = sess["id"]
        lines.extend(
            [
                f"## Session {si} — `{sid[:8]}…`",
                "",
                f"- **ID complet** : `{sid}`",
                f"- **Dernière activité** : {dt}",
                f"- **Nombre de prompts** : {len(sess['prompts'])}",
                f"- **Fichier source** : `agent-transcripts/{sid}/{sid}.jsonl`",
                "",
            ]
        )
        for pi, p in enumerate(sess["prompts"], 1):
            tag = " *(message avec image)*" if p["has_image"] else ""
            lines.extend(
                [
                    f"### Prompt {si}.{pi}{tag}",
                    "",
                    p["text"],
                    "",
                    "---",
                    "",
                ]
            )
    return "\n".join(lines).rstrip()


def merge():
    if not V1.exists():
        raise SystemExit(f"Fichier introuvable : {V1}")

    sessions = load_sessions()
    total = sum(len(s["prompts"]) for s in sessions)
    prompts_body = build_prompts_body(sessions)

    v1_text = V1.read_text(encoding="utf-8")
    if MARKER_13 not in v1_text or MARKER_14 not in v1_text:
        raise SystemExit("Marqueurs section 13/14 introuvables dans bdm_v1.md")

    before_13 = v1_text.split(MARKER_13)[0].rstrip()
    after_14 = v1_text.split(MARKER_14, 1)[1]

    section_13 = f"""{MARKER_13}

> Tous tes messages utilisateur Cursor, **texte intégral**, extraits des transcripts locaux.  
> **Total : {total} prompts** dans **{len(sessions)} sessions**.  
> Source : `C:\\Users\\cisse\\.cursor\\projects\\c-xampp-htdocs-BDM\\agent-transcripts\\`

{prompts_body}"""

    merged = before_13 + "\n\n---\n\n" + section_13 + "\n\n---\n\n" + MARKER_14 + after_14
    V1.write_text(merged, encoding="utf-8")
    print(f"Mis à jour : {V1} ({len(merged.splitlines())} lignes, {total} prompts)")


if __name__ == "__main__":
    merge()
