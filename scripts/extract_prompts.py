"""Regénère la section 13 de bdm_v1.md depuis les transcripts Cursor."""

import subprocess
import sys
from pathlib import Path

merge = Path(__file__).resolve().parent / "merge_bdm_v1.py"
subprocess.run([sys.executable, str(merge)], check=True)
