#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""Export Cursor agent transcripts to a markdown Q&A file."""

import json
import re
from datetime import datetime
from pathlib import Path

BASE = Path(r"C:\Users\cisse\.cursor\projects\c-xampp-htdocs-BDM\agent-transcripts")
OUT = Path(r"c:\xampp\htdocs\BDM\historique_prompts_reponses.md")
# Cap very long assistant replies for readability (chars)
MAX_ASSISTANT_CHARS = 12000


def extract_text(content):
    if isinstance(content, str):
        return content
    if isinstance(content, list):
        parts = []
        for block in content:
            if isinstance(block, dict) and block.get("type") == "text":
                parts.append(block.get("text", ""))
        return "\n".join(parts)
    return ""


def clean_user(text):
    text = re.sub(r"<timestamp>.*?</timestamp>\s*", "", text, flags=re.S)
    m = re.search(r"<user_query>\s*(.*?)\s*</user_query>", text, flags=re.S)
    if m:
        return m.group(1).strip()
    return text.strip()


def clean_assistant(text):
    return text.replace("[REDACTED]", "").strip()


def truncate(text, limit=MAX_ASSISTANT_CHARS):
    if len(text) <= limit:
        return text
    return (
        text[:limit].rstrip()
        + "\n\n…\n\n*[Réponse tronquée — trop longue dans le transcript]*"
    )


def load_sessions():
    files = sorted(
        [p for p in BASE.rglob("*.jsonl") if "subagents" not in p.parts],
        key=lambda p: p.stat().st_mtime,
    )
    sessions = []
    for f in files:
        exchanges = []
        current_user = None
        assistant_parts = []
        with open(f, "r", encoding="utf-8", errors="replace") as fh:
            for line in fh:
                line = line.strip()
                if not line:
                    continue
                try:
                    obj = json.loads(line)
                except json.JSONDecodeError:
                    continue
                role = obj.get("role")
                msg = obj.get("message", {})
                text = extract_text(msg.get("content", ""))
                if role == "user":
                    if current_user is not None:
                        exchanges.append(
                            {
                                "q": current_user,
                                "a": "\n\n".join(
                                    p for p in assistant_parts if p
                                ).strip(),
                            }
                        )
                    current_user = clean_user(text)
                    assistant_parts = []
                elif role == "assistant":
                    cleaned = clean_assistant(text)
                    if cleaned:
                        assistant_parts.append(cleaned)
        if current_user is not None:
            exchanges.append(
                {
                    "q": current_user,
                    "a": "\n\n".join(p for p in assistant_parts if p).strip(),
                }
            )
        mtime = datetime.fromtimestamp(f.stat().st_mtime)
        sessions.append(
            {
                "id": f.parent.name,
                "date": mtime.strftime("%Y-%m-%d %H:%M"),
                "exchanges": exchanges,
            }
        )
    return sessions


def md_escape_fence(text):
    # Avoid breaking outer fences if response contains ```
    return text.replace("```", "``\u200b`")


def build_md(sessions):
    lines = []
    lines.append("# Historique des prompts et réponses — BDM")
    lines.append("")
    lines.append(
        f"*Généré le {datetime.now().strftime('%Y-%m-%d %H:%M')} "
        "à partir des transcripts Cursor Agent.*"
    )
    lines.append("")
    total_q = sum(len(s["exchanges"]) for s in sessions)
    lines.append(f"- **Sessions** : {len(sessions)}")
    lines.append(f"- **Échanges** : {total_q}")
    lines.append("")
    lines.append("---")
    lines.append("")

    global_n = 0
    for si, session in enumerate(sessions, 1):
        lines.append(
            f"## Session {si} — {session['date']}"
        )
        lines.append("")
        lines.append(f"*Transcript : `{session['id']}`*")
        lines.append("")
        if not session["exchanges"]:
            lines.append("*(Aucun échange)*")
            lines.append("")
            continue
        for ei, ex in enumerate(session["exchanges"], 1):
            global_n += 1
            lines.append(f"### Échange {global_n} (S{si}.{ei})")
            lines.append("")
            lines.append("#### 🧑 Prompt / question")
            lines.append("")
            lines.append(ex["q"] if ex["q"] else "*(vide)*")
            lines.append("")
            lines.append("#### 🤖 Réponse")
            lines.append("")
            answer = ex["a"] if ex["a"] else "*(pas de réponse textuelle enregistrée)*"
            answer = truncate(answer)
            lines.append(md_escape_fence(answer))
            lines.append("")
            lines.append("---")
            lines.append("")
    return "\n".join(lines)


def main():
    sessions = load_sessions()
    md = build_md(sessions)
    OUT.write_text(md, encoding="utf-8")
    print(f"Wrote {OUT}")
    print(f"Sessions: {len(sessions)}")
    print(f"Exchanges: {sum(len(s['exchanges']) for s in sessions)}")
    print(f"Size bytes: {OUT.stat().st_size}")


if __name__ == "__main__":
    main()
